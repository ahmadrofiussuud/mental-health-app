"use client";

import { useSession } from "next-auth/react";
import { useEffect, useState } from "react";
import Image from "next/image";
import { formatDistanceToNow } from "date-fns";
import { id } from "date-fns/locale";
import { Search, Filter, BookOpen } from "lucide-react";

// Mock data for MVP
const mockJournals = [
    {
        id: "1",
        studentName: "Siswa 1",
        avatar: "S1",
        mood: "Happy",
        title: "Hari yang menyenangkan",
        content: "Hari ini saya belajar banyak hal baru di sekolah. Teman-teman sangat membantu.",
        createdAt: new Date(Date.now() - 1000 * 60 * 60 * 2), // 2 hours ago
    },
    {
        id: "2",
        studentName: "Siswa 2",
        avatar: "S2",
        mood: "Sad",
        title: "Sedikit lelah",
        content: "Tugas matematika hari ini sangat sulit. Saya merasa sedikit putus asa.",
        createdAt: new Date(Date.now() - 1000 * 60 * 60 * 24), // 1 day ago
    },
    {
        id: "3",
        studentName: "Siswa 3",
        avatar: "S3",
        mood: "Anxious",
        title: "Ujian besok",
        content: "Saya sangat gugup menghadapi ujian biologi besok. Semoga bisa mengerjakannya.",
        createdAt: new Date(Date.now() - 1000 * 60 * 60 * 48), // 2 days ago
    },
];

export default function TeacherJournalsPage() {
    const { data: session } = useSession();
    const [mounted, setMounted] = useState(false);

    useEffect(() => {
        setMounted(true);
    }, []);

    if (!mounted) return null;

    return (
        <div className="min-h-screen bg-gray-50 pb-20">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">Log Jurnal Siswa</h1>
                        <p className="text-sm text-gray-500 mt-1">Pantau aktivitas dan perasaan siswa melalui jurnal harian mereka.</p>
                    </div>

                    <div className="flex gap-2">
                        <div className="relative">
                            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                            <input
                                type="text"
                                placeholder="Cari siswa..."
                                className="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 w-full md:w-64"
                            />
                        </div>
                        <button className="p-2 border border-gray-200 rounded-lg hover:bg-gray-50 text-gray-600">
                            <Filter className="w-5 h-5" />
                        </button>
                    </div>
                </div>

                <div className="grid gap-6">
                    {mockJournals.map((journal) => (
                        <div key={journal.id} className="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                            <div className="flex items-start gap-4">
                                <div className="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold shrink-0">
                                    {journal.avatar}
                                </div>
                                <div className="flex-1 min-w-0">
                                    <div className="flex items-center justify-between mb-2">
                                        <h3 className="text-base font-semibold text-gray-900">{journal.studentName}</h3>
                                        <span className="text-xs text-gray-400 whitespace-nowrap">
                                            {formatDistanceToNow(journal.createdAt, { addSuffix: true, locale: id })}
                                        </span>
                                    </div>

                                    <div className="flex items-center gap-2 mb-3">
                                        <span className={`inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                      ${journal.mood === 'Happy' ? 'bg-green-100 text-green-700' :
                                                journal.mood === 'Sad' ? 'bg-blue-100 text-blue-700' :
                                                    journal.mood === 'Anxious' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700'}`}>
                                            {journal.mood}
                                        </span>
                                        <span className="text-gray-300">•</span>
                                        <span className="text-sm font-medium text-gray-700">{journal.title}</span>
                                    </div>

                                    <p className="text-gray-600 text-sm leading-relaxed mb-4">
                                        {journal.content}
                                    </p>

                                    <div className="flex items-center gap-4 pt-4 border-t border-gray-50">
                                        <button className="text-sm text-teal-600 font-medium hover:text-teal-700 flex items-center gap-1">
                                            <BookOpen className="w-4 h-4" /> Baca Selengkapnya
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}
