import { NextAuthOptions } from "next-auth";
import CredentialsProvider from "next-auth/providers/credentials";
import { MOCK_USERS } from "@/lib/mock-data";
import { compare } from "bcryptjs"; // Optional: keep if we want to simulate hash check, or remove

export const authOptions: NextAuthOptions = {
    // Adapter removed for mock mode
    session: { strategy: "jwt" },
    pages: { signIn: "/login" },
    providers: [
        CredentialsProvider({
            name: "Credentials",
            credentials: {
                email: { label: "Email", type: "email" },
                password: { label: "Password", type: "password" }
            },
            async authorize(credentials) {
                if (!credentials?.email || !credentials?.password) return null;

                // MOCK AUTH LOGIC
                const user = MOCK_USERS.find(u => u.email === credentials.email);

                // For simplicity in mock mode, we accept "password" or skip hash check
                // In production, we would use: const isValid = await compare(credentials.password, user.password);

                if (!user) return null;

                // Allow specific password "password" or just bypass for admin/admin convenience if requested
                // User said: "simple credential provider that accepts any login (e.g., admin/admin)"
                // Let's create a generic admin if not found in mock data

                if (credentials.email === "admin" && credentials.password === "admin") {
                    return {
                        id: "admin-1",
                        name: "Super Admin",
                        email: "admin@example.com",
                        role: "ADMIN"
                    };
                }

                if (user.password !== credentials.password) return null;

                return {
                    id: user.id,
                    email: user.email,
                    name: user.name,
                    role: user.role
                };
            }
        })
    ],
    callbacks: {
        async session({ session, token }) {
            if (token) {
                session.user.id = token.id as string;
                session.user.role = token.role as string;
            }
            return session;
        },
        async jwt({ token, user }) {
            if (user) {
                token.id = user.id;
                token.role = user.role;
            }
            return token;
        }
    }
};
