import { NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { db } from "@/lib/db";
import { z } from "zod";

const MoodSchema = z.object({
    mood: z.enum(["HAPPY", "CALM", "NEUTRAL", "SAD", "ANGRY"]),
    note: z.string().optional(),
});

export async function POST(req: Request) {
    const session = await getServerSession(authOptions);
    if (!session) return new NextResponse("Unauthorized", { status: 401 });

    const body = await req.json();
    const parsed = MoodSchema.safeParse(body);

    if (!parsed.success) {
        return NextResponse.json({ error: "Invalid Input" }, { status: 400 });
    }

    try {
        const moodLog = await db.moodLog.create({
            data: {
                userId: session.user.id,
                mood: parsed.data.mood,
                note: parsed.data.note,
            },
        });
        return NextResponse.json(moodLog);
    } catch (error) {
        return new NextResponse("Internal Error", { status: 500 });
    }
}

export async function GET(req: Request) {
    const session = await getServerSession(authOptions);
    if (!session) return new NextResponse("Unauthorized", { status: 401 });

    try {
        const logs = await db.moodLog.findMany({
            where: { userId: session.user.id },
            orderBy: { createdAt: "desc" },
            take: 10
        });
        return NextResponse.json(logs);
    } catch (error) {
        return new NextResponse("Internal Error", { status: 500 });
    }
}
