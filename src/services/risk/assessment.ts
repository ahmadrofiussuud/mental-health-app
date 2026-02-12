import { db } from "@/lib/db";
import { logAudit } from "@/services/audit";
import { generateAIResponse } from "@/services/ai/gemini";
import { z } from "zod";

const RiskSchema = z.object({
    risk_score: z.number().min(0).max(100),
    risk_level: z.enum(["LOW", "MEDIUM", "HIGH", "CRITICAL"]),
    summary: z.string(),
    factors: z.array(z.string()).optional().default([])
});

export async function runRiskAssessment(studentId: string, actorId: string) {
    // 1. Fetch Journals (30 days)
    const thirtyDaysAgo = new Date();
    thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);

    const journals = await db.journal.findMany({
        where: { userId: studentId, createdAt: { gte: thirtyDaysAgo } },
        select: { content: true, createdAt: true, mood: true }
    });

    if (journals.length === 0) return null;

    // 2. Prepare Context (Token Optimization: summarize if too long)
    // Simple optimization: Limit chars per journal
    const context = journals.map(j =>
        `[${j.createdAt.toISOString()}] Mood: ${j.mood || 'UNKNOWN'} Content: ${j.content.substring(0, 500)}`
    ).join("\n");

    // 3. Generate with AI
    const prompt = `
    Analyze these student journals for mental health risks.
    Journals:
    ${context}
    
    Return JSON: { "risk_score": 0-100, "risk_level": "LOW"|"MEDIUM"|"HIGH"|"CRITICAL", "summary": "string", "factors": ["string"] }
  `;

    const aiResult = await generateAIResponse(prompt, actorId);
    const validated = RiskSchema.parse(aiResult);

    // 4. Save to DB
    const riskProfile = await db.riskProfile.upsert({
        where: { userId: studentId },
        create: {
            userId: studentId,
            currentScore: validated.risk_score,
            riskLevel: validated.risk_level,
            summary: validated.summary,
            riskFactors: validated.factors,
            lastAssessment: new Date()
        },
        update: {
            currentScore: validated.risk_score,
            riskLevel: validated.risk_level,
            summary: validated.summary,
            riskFactors: validated.factors,
            lastAssessment: new Date()
        }
    });

    // Track history
    await db.riskAssessment.create({
        data: {
            riskProfileId: riskProfile.id,
            score: validated.risk_score,
            reason: validated.summary,
            factors: validated.factors
        }
    });

    // 5. Audit Log
    await logAudit({
        actorId,
        targetUserId: studentId,
        action: "RUN_RISK_ASSESSMENT",
        resourceId: riskProfile.id
    });

    return validated;
}
