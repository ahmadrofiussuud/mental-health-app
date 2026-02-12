import { NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { runRiskAssessment } from "@/services/risk/assessment";
import { z } from "zod";

const Schema = z.object({ studentId: z.string() });

export async function POST(req: Request) {
    const session = await getServerSession(authOptions);

    // RBAC
    if (!session || (session.user.role !== "TEACHER" && session.user.role !== "ADMIN")) {
        return new NextResponse("Unauthorized", { status: 401 });
    }

    const body = await req.json();
    const parsed = Schema.safeParse(body);

    if (!parsed.success) {
        return NextResponse.json({ error: "Invalid Input" }, { status: 400 });
    }

    const { studentId } = parsed.data;

    try {
        const result = await runRiskAssessment(studentId, session.user.id);
        return NextResponse.json(result);
    } catch (error) {
        console.error(error);
        return new NextResponse("Error running assessment", { status: 500 });
    }
}
