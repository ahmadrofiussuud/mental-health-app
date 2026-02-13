import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import Image from "next/image";
import Link from "next/link";
import prisma from "@/lib/prisma";


async function getStudentStats(userId: string) {
    const totalEntries = await prisma.journal.count({
        where: { userId },
    });

    // Calculate streak (simplified version)
    const journals = await prisma.journal.findMany({
        where: { userId },
        select: { createdAt: true },
        orderBy: { createdAt: "desc" },
    });

    // Basic streak calc would go here, returning 0 for now to match MVP speed
    const streak = journals.length > 0 ? 1 : 0;

    return { totalEntries, streak };
}

async function getDailyWisdom() {
    // Static for now, can be dynamic later
    return {
        emoji: "🌟",
        title: "Percaya Diri",
        quote: "Kekuatan terbesar bukan pada tidak pernah jatuh, tapi bangkit setiap kali kita jatuh.",
    };
}

export default async function StudentDashboard() {
    const session = await getServerSession(authOptions);
    if (!session) return null;

    const stats = await getStudentStats(session.user.id);
    const wisdom = await getDailyWisdom();

    return (
        <div className="py-12">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                {/* Hero Banner */}
                <div className="relative w-full rounded-[2.5rem] md:rounded-[3rem] overflow-hidden shadow-sm group">
                    {/* Background Image Container - Absolute on Desktop to allow overlay */}
                    <div className="absolute inset-0 bg-slate-100 hidden md:block">
                        <Image
                            src="/images/school-bus.png"
                            alt="School Bus"
                            fill
                            className="object-cover object-center"
                            priority
                        />
                        <div className="absolute inset-0 bg-black/10" />
                    </div>

                    {/* Mobile Image - Relative */}
                    <div className="relative h-56 w-full md:hidden bg-slate-100">
                        <Image
                            src="/images/school-bus.png"
                            alt="School Bus"
                            fill
                            className="object-cover object-center"
                            priority
                        />
                        <div className="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent" />
                    </div>

                    {/* Content Container - Defines height on Desktop via Padding */}
                    <div className="relative z-10 flex flex-col md:flex-row items-center md:min-h-[420px] px-4 pb-6 md:p-12">
                        {/* Card */}
                        <div className="bg-white/95 backdrop-blur-sm p-6 md:p-10 rounded-[2rem] shadow-xl md:shadow-2xl border border-white/50 w-full max-w-xl -mt-10 md:mt-0 transform transition duration-500 hover:scale-[1.01]">
                            <div className="inline-flex items-center gap-2 mb-4 bg-teal-50 px-3 py-1 rounded-full border border-teal-100">
                                <span className="relative flex h-2.5 w-2.5">
                                    <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                                    <span className="relative inline-flex rounded-full h-2.5 w-2.5 bg-teal-500"></span>
                                </span>
                                <span className="text-[10px] font-bold text-teal-600 uppercase tracking-widest">
                                    Student Portal
                                </span>
                            </div>

                            <h1 className="text-2xl md:text-4xl font-black text-slate-900 mb-3 md:mb-4 leading-tight tracking-tight">
                                Hello, <span className="text-indigo-600">{session.user.name}</span>! 🎒
                            </h1>

                            <p className="text-slate-600 font-medium text-sm md:text-base mb-6 leading-relaxed border-l-4 border-indigo-100 pl-4 py-1">
                                &quot;Setiap hari adalah awal yang baru. Teruslah belajar, berkembang, dan percaya pada dirimu sendiri!&quot;
                            </p>

                            <div className="flex flex-col sm:flex-row gap-3">
                                <Link
                                    href="/student/mood"
                                    className="inline-flex justify-center items-center gap-2 bg-[#4F46E5] hover:bg-[#4338ca] text-white px-6 py-3.5 rounded-xl font-bold text-sm transition shadow-lg shadow-indigo-200 hover:shadow-indigo-300 transform active:scale-95"
                                >
                                    🙂 Cek Mood
                                </Link>

                                <Link
                                    href="/student/journals"
                                    className="inline-flex justify-center items-center gap-2 bg-white border-2 border-slate-100 text-slate-700 hover:bg-slate-50 hover:border-slate-200 px-6 py-3.5 rounded-xl font-bold text-sm transition transform active:scale-95 shadow-sm"
                                >
                                    📝 Tulis Jurnal
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Dashboard Grid */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Stats Section */}
                    <div className="lg:col-span-2 space-y-6">
                        <div className="flex items-center gap-3 pl-2">
                            <div className="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <h2 className="text-2xl font-bold text-slate-800">Perjalananmu</h2>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {/* Streak Card */}
                            <div className="relative bg-gradient-to-br from-[#F97316] to-[#EA580C] rounded-[2rem] p-8 text-white h-64 shadow-xl shadow-orange-200/50 hover:shadow-2xl transition duration-300 overflow-hidden group">
                                <div className="flex justify-between items-start mb-8 relative z-10">
                                    <div className="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl shadow-inner border border-white/10">🔥</div>
                                    <span className="bg-white/20 backdrop-blur-md px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider border border-white/10">Streak</span>
                                </div>
                                <div className="absolute bottom-8 left-8 relative z-10">
                                    <h3 className="text-7xl font-black mb-2 tracking-tighter leading-none">{stats.streak}</h3>
                                    <p className="text-orange-50 font-bold text-sm opacity-90">Hari berturut-turut!</p>
                                </div>
                            </div>

                            {/* Entries Card */}
                            <div className="relative bg-gradient-to-br from-[#8B5CF6] to-[#7C3AED] rounded-[2rem] p-8 text-white h-64 shadow-xl shadow-purple-200/50 hover:shadow-2xl transition duration-300 overflow-hidden group">
                                <div className="flex justify-between items-start mb-8 relative z-10">
                                    <div className="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl shadow-inner border border-white/10">✍️</div>
                                    <span className="bg-white/20 backdrop-blur-md px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider border border-white/10">Jurnal</span>
                                </div>
                                <div className="absolute bottom-8 left-8 relative z-10">
                                    <h3 className="text-7xl font-black mb-2 tracking-tighter leading-none">{stats.totalEntries}</h3>
                                    <p className="text-purple-50 font-bold text-sm opacity-90">Total Jurnal Ditulis</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Wisdom Section */}
                    <div className="space-y-6">
                        <div className="flex items-center gap-3 pl-2">
                            <div className="p-2 bg-yellow-100 rounded-lg text-yellow-600">
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <h2 className="text-2xl font-bold text-slate-800">Kata Bijak</h2>
                        </div>
                        <div className="bg-[#FEFCE8] rounded-[2rem] p-8 h-64 flex flex-col justify-center items-center text-center shadow-lg shadow-yellow-100/50 border border-yellow-100 relative overflow-hidden group hover:shadow-xl transition duration-300">
                            <div className="relative z-10 w-16 h-16 bg-white rounded-2xl shadow-md flex items-center justify-center text-4xl mb-6 transform group-hover:-rotate-12 transition duration-300 ease-out">{wisdom.emoji}</div>
                            <div className="relative z-10 px-2">
                                <h3 className="text-xl font-black text-slate-800 mb-2">{wisdom.title}</h3>
                                <p className="text-slate-600 font-medium text-sm leading-relaxed">&quot;{wisdom.quote}&quot;</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
