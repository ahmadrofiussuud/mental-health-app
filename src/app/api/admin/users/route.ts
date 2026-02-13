import { NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { db } from "@/lib/db";
import { hash } from "bcryptjs";
import { z } from "zod";

const CreateUserSchema = z.object({
    name: z.string().min(1),
    email: z.string().email(),
    password: z.string().min(6),
    role: z.enum(["STUDENT", "TEACHER", "ADMIN"]),
});

export async function POST(req: Request) {
    const session = await getServerSession(authOptions);

    if (!session || session.user.role !== "ADMIN") {
        return new NextResponse("Unauthorized", { status: 401 });
    }

    const body = await req.json();
    const parsed = CreateUserSchema.safeParse(body);

    if (!parsed.success) {
        return NextResponse.json({ error: "Invalid Data" }, { status: 400 });
    }

    const { name, email, password, role } = parsed.data;
    const hashedPassword = await hash(password, 12);

    try {
        const user = await db.user.create({
            data: {
                name,
                email,
                password: hashedPassword,
                // eslint-disable-next-line @typescript-eslint/no-explicit-any
                role: role as any
            }
        });
        return NextResponse.json(user);
    } catch {
        return NextResponse.json({ error: "Email already exists" }, { status: 409 });
    }
}
