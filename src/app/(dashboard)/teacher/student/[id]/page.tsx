import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { db } from "@/lib/db";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { notFound } from "next/navigation";
import RiskAssessmentButton from "./RiskAssessmentButton";

export default async function StudentDetailPage(props: { params: Promise<{ id: string }> }) {
    const params = await props.params;
    const session = await getServerSession(authOptions);
    if (!session) return null;

    const student = await db.user.findUnique({
        where: { id: params.id },
        include: {
            riskProfile: true,
            journals: { orderBy: { createdAt: 'desc' }, take: 5 },
            moodLogs: { orderBy: { createdAt: 'desc' }, take: 5 }
        }
    });

    if (!student || student.role !== 'STUDENT') {
        return notFound();
    }

    return (
        <div className="space-y-6">
            <div className="flex justify-between items-center">
                <div>
                    <h1 className="text-2xl font-bold">{student.name}</h1>
                    <p className="text-gray-500">{student.email}</p>
                </div>
                <div>
                    <RiskAssessmentButton studentId={student.id} />
                </div>
            </div>

            {/* Risk Profile Section */}
            <Card className={student.riskProfile?.currentScore && student.riskProfile.currentScore > 50 ? "border-red-500 bg-red-50" : "bg-green-50 border-green-500"}>
                <CardHeader>
                    <CardTitle>Risk Profile (AI Analysis)</CardTitle>
                </CardHeader>
                <CardContent>
                    <div className="grid grid-cols-2 gap-4">
                        <div>
                            <span className="block text-sm text-gray-500">Risk Score</span>
                            <span className="text-3xl font-bold">{student.riskProfile?.currentScore ?? 0}/100</span>
                        </div>
                        <div>
                            <span className="block text-sm text-gray-500">Level</span>
                            <span className="text-xl font-bold">{student.riskProfile?.riskLevel ?? "UNKNOWN"}</span>
                        </div>
                    </div>
                    <div className="mt-4">
                        <h4 className="font-semibold text-sm">Summary</h4>
                        <p className="text-sm mt-1">{student.riskProfile?.summary || "Belum ada assessment."}</p>
                    </div>
                </CardContent>
            </Card>

            <div className="grid md:grid-cols-2 gap-6">
                {/* Recent Journals */}
                <Card>
                    <CardHeader><CardTitle>Jurnal Terakhir</CardTitle></CardHeader>
                    <CardContent>
                        <div className="space-y-4">
                            {student.journals.length === 0 && <p className="text-gray-500 text-sm">Belum ada jurnal.</p>}
                            {student.journals.map(j => (
                                <div key={j.id} className="border-b pb-2 last:border-0">
                                    <div className="flex justify-between text-xs text-gray-500 mb-1">
                                        <span>{new Date(j.createdAt).toLocaleDateString()}</span>
                                        <span>{j.mood}</span>
                                    </div>
                                    <p className="text-sm line-clamp-3">{j.content}</p>
                                    {j.isAnonymous && <span className="text-xs bg-gray-200 px-1 rounded">Anon</span>}
                                </div>
                            ))}
                        </div>
                    </CardContent>
                </Card>

                {/* Recent Moods */}
                <Card>
                    <CardHeader><CardTitle>Mood Log</CardTitle></CardHeader>
                    <CardContent>
                        <div className="space-y-2">
                            {student.moodLogs.length === 0 && <p className="text-gray-500 text-sm">Belum ada mood log.</p>}
                            {student.moodLogs.map(m => (
                                <div key={m.id} className="flex justify-between items-center text-sm p-2 bg-gray-50 rounded">
                                    <span>{new Date(m.createdAt).toLocaleString()}</span>
                                    <span className="font-bold">{m.mood}</span>
                                </div>
                            ))}
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    );
}
