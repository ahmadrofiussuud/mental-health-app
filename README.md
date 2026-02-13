# Serenity Hub (Database-less Edition)

This is a **Database-less** version of the Serenity Hub application.

## Features
- **Mock Data**: Uses in-memory data for Users, Journals, and Mood Logs (`src/lib/mock-data.ts`).
- **AI Powered**: Fully functional integration with Google Gemini AI.
- **Authentication**: Supports login with any credentials found in mock data.

## Login Credentials
You can login with the following accounts:
- **Admin**: `admin` / `admin`
- **Teacher**: `teacher@example.com` / `password`
- **Student**: `student@example.com` / `password`

## Deployment
This project is optimized for Vercel.
**Environment Variables Required:**
- `NEXTAUTH_SECRET`: Generate a random string (e.g., `openssl rand -base64 32`).
- `NEXTAUTH_URL`: Your Vercel URL (e.g., `https://your-project.vercel.app`).
- `GOOGLE_GEMINI_API_KEY`: Your Google AI Studio API Key.
