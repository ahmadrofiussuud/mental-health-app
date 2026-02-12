import { TopNavigation } from "@/components/layout/TopNavigation";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { redirect } from "next/navigation";
import AuthProvider from "@/components/providers/AuthProvider";

export default async function DashboardLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    const session = await getServerSession(authOptions);

    if (!session) {
        redirect("/login");
    }

    return (
        <AuthProvider>
            <div className="min-h-screen bg-gray-50">
                <TopNavigation />
                <main>
                    {children}
                </main>
            </div>
        </AuthProvider>
    );
}
