import { GoogleGenerativeAI } from "@google/generative-ai";

const apiKey = process.env.GEMINI_API_KEY || process.env.GOOGLE_GEMINI_API_KEY;

if (!apiKey) {
    throw new Error("Missing GEMINI_API_KEY or GOOGLE_GEMINI_API_KEY environment variable");
}

const genAI = new GoogleGenerativeAI(apiKey);

// Use a model that supports text generation. Using gemini-2.5-flash as confirmed by script.
export const model = genAI.getGenerativeModel({ model: "gemini-2.5-flash" });
