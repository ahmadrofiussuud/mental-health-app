import { GoogleGenerativeAI } from "@google/generative-ai";

const genAI = new GoogleGenerativeAI(process.env.GOOGLE_GEMINI_API_KEY!);

export async function generateAIResponse(prompt: string, userId: string) {
    // Rate Link removed for database-less pivot


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
