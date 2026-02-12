import { GoogleGenerativeAI } from "@google/generative-ai";
import { Ratelimit } from "@upstash/ratelimit";
import { redis } from "@/lib/redis";

const genAI = new GoogleGenerativeAI(process.env.GOOGLE_GEMINI_API_KEY!);

// Rate Limit: 10 requests per 60s per user
const ratelimit = new Ratelimit({
    redis,
    limiter: Ratelimit.slidingWindow(10, "60 s"),
});

export async function generateAIResponse(prompt: string, userId: string) {
    // 1. Check Rate Limit
    const { success } = await ratelimit.limit(userId);
    if (!success) {
        throw new Error("RATE_LIMIT_EXCEEDED");
    }

    // 2. Call Gemini
    try {
        const model = genAI.getGenerativeModel({
            model: "gemini-2.0-flash",
            generationConfig: { responseMimeType: "application/json" } // Structured
        });

        const result = await model.generateContent(prompt);
        const text = result.response.text();

        return JSON.parse(text);
    } catch (error) {
        console.error("Gemini Error:", error);
        throw new Error("AI_SERVICE_UNAVAILABLE");
    }
}
