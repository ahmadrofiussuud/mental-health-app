import { GoogleGenerativeAI } from "@google/generative-ai";

const apiKey = process.env.GEMINI_API_KEY || process.env.GOOGLE_GEMINI_API_KEY;

if (!apiKey) {
    throw new Error("Missing GEMINI_API_KEY or GOOGLE_GEMINI_API_KEY environment variable");
}

const genAI = new GoogleGenerativeAI(apiKey);

// Use a model that supports text generation (e.g., gemini-1.5-flash which is fast and cheap)
export const model = genAI.getGenerativeModel({ model: "gemini-1.5-flash" });
