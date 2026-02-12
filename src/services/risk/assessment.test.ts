import { expect, test, describe } from 'vitest'
import { z } from 'zod'

const RiskSchema = z.object({
    risk_score: z.number().min(0).max(100),
    risk_level: z.enum(["LOW", "MEDIUM", "HIGH", "CRITICAL"]),
    summary: z.string(),
    factors: z.array(z.string()).optional().default([])
});

describe('Risk Assessment Logic', () => {
    test('validates correct AI JSON output', () => {
        const input = {
            risk_score: 85,
            risk_level: "HIGH",
            summary: "Student shows signs of depression.",
            factors: ["academic_stress", "isolation"]
        };

        const result = RiskSchema.safeParse(input);
        expect(result.success).toBe(true);
        if (result.success) {
            expect(result.data.risk_score).toBe(85);
        }
    });

    test('rejects invalid scores', () => {
        const input = {
            risk_score: 150, // Invalid > 100
            risk_level: "HIGH",
            summary: "Invalid score test"
        };
        const result = RiskSchema.safeParse(input);
        expect(result.success).toBe(false);
    });
});
