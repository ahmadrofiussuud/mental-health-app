import { NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { db } from "@/lib/db";
import { z } from "zod";

const JournalSchema = z.object({
    title: z.string().min(1, "Title is required"),
    content: z.string().min(1, "Content is required"),
    mood: z.enum(["HAPPY", "CALM", "NEUTRAL", "SAD", "ANGRY"]).optional(),
    isAnonymous: z.boolean().default(false),
});

export async function POST(req: Request) {
    const session = await getServerSession(authOptions);
    if (!session) return new NextResponse("Unauthorized", { status: 401 });

    const body = await req.json();
    const parsed = JournalSchema.safeParse(body);

    if (!parsed.success) {
        return NextResponse.json({ error: parsed.error.issues }, { status: 400 });
    }

    const { title, content, mood, isAnonymous } = parsed.data;

    try {
        const journal = await db.journal.create({
            data: {
                userId: session.user.id,
                title,
                content,
                mood,
                isAnonymous,
            },
        });
        return NextResponse.json(journal);
    } catch (error) {
        console.error("Journal creation error:", error);
        return new NextResponse("Internal Error", { status: 500 });
    }
}

export async function GET(req: Request) {
    const session = await getServerSession(authOptions);
    if (!session) return new NextResponse("Unauthorized", { status: 401 });

    try {
        const journals = await db.journal.findMany({
            where: { userId: session.user.id },
            orderBy: { createdAt: "desc" },
        });
        return NextResponse.json(journals);
    } catch (error) {
        return new NextResponse("Internal Error", { status: 500 });
    }
}
