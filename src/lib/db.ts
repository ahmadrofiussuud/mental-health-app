import { MOCK_USERS, MOCK_MOODS, MOCK_JOURNALS } from "./mock-data";

// Mock Prisma Client to avoid rewriting all service calls
export const db = {
    user: {
        findUnique: async ({ where }: any) => {
            return MOCK_USERS.find(u => u.email === where.email || u.id === where.id) || null;
        },
        findFirst: async ({ where }: any) => {
            return MOCK_USERS.find(u => u.email === where.email) || null;
        }
    },
    journal: {
        findMany: async ({ where, orderBy }: any) => {
            let results = [...MOCK_JOURNALS];
            if (where?.userId) {
                results = results.filter(j => j.userId === where.userId);
            }

            // Join with user for consistency
            const resultsWithUser = results.map(j => {
                const user = MOCK_USERS.find(u => u.id === j.userId);
                return { ...j, user: user ? { name: user.name, ...user } : { name: "Unknown" } };
            });

            // Mock sorting (simple)
            if (orderBy?.createdAt === 'desc') {
                resultsWithUser.sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime());
            }
            return resultsWithUser;
        },
        create: async ({ data }: any) => {
            const newJournal = {
                id: `journal-${Date.now()}`,
                ...data,
                createdAt: new Date().toISOString(),
                updatedAt: new Date().toISOString(),
            };
            MOCK_JOURNALS.push(newJournal); // In-memory write
            return newJournal;
        },
        count: async ({ where }: any) => {
            if (where?.userId) {
                return MOCK_JOURNALS.filter(j => j.userId === where.userId).length;
            }
            return MOCK_JOURNALS.length;
        }
    },
    moodLog: {
        findMany: async ({ where, orderBy, include }: any) => {
            let results = [...MOCK_MOODS];
            if (where?.userId) {
                results = results.filter(m => m.userId === where.userId);
            }
            if (where?.createdAt?.gte) {
                // Simple date filter mock
                results = results.filter(m => new Date(m.createdAt) >= new Date(where.createdAt.gte));
            }

            // Join with user if needed (simplified: always join for prototype stability)
            const resultsWithUser = results.map(log => {
                const user = MOCK_USERS.find(u => u.id === log.userId);
                return { ...log, user: user ? { name: user.name, ...user } : { name: "Unknown" } };
            });

            if (orderBy?.createdAt === 'desc') {
                resultsWithUser.sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime());
            }
            return resultsWithUser;
        },
        create: async ({ data }: any) => {
            const newMood = {
                id: `mood-${Date.now()}`,
                ...data,
                createdAt: new Date().toISOString(),
            };
            MOCK_MOODS.push(newMood);
            return newMood;
        }
    }
};

// Global for development to prevent reloading resets (partially)
const globalForPrisma = global as unknown as { prisma: typeof db };
if (process.env.NODE_ENV !== "production") globalForPrisma.prisma = db;
