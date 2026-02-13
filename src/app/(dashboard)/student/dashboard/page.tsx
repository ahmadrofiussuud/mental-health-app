import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import Link from "next/link";
import { db } from "@/lib/db";

async function getStudentStats(userId: string) {
    const totalEntries = await db.journal.count({ where: { userId } });
    const journals = await db.journal.findMany({
        where: { userId },
        orderBy: { createdAt: "desc" },
    });
    const streak = journals.length > 0 ? 1 : 0;
    return { totalEntries, streak };
}

const DAILY_QUOTES = [
    { emoji: "🌟", title: "Percaya Diri", quote: "Kekuatan terbesar bukan pada tidak pernah jatuh, tapi bangkit setiap kali kita jatuh." },
    { emoji: "🌱", title: "Bertumbuh", quote: "Setiap langkah kecil yang kamu ambil hari ini membawa perubahan besar di masa depan." },
    { emoji: "💪", title: "Kuat", quote: "Kamu lebih kuat dari yang kamu pikirkan. Teruslah melangkah." },
];

export default async function StudentDashboard() {
    const session = await getServerSession(authOptions);
    if (!session) return null;

    const stats = await getStudentStats(session.user.id);
    const wisdom = DAILY_QUOTES[Math.floor(Math.random() * DAILY_QUOTES.length)];

    return (
        <div className="min-h-screen bg-gray-50">
            <div className="max-w-4xl mx-auto px-4 py-6 sm:py-10 space-y-6 sm:space-y-8">

                {/* Hero Welcome Card */}
                <div className="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-gradient-to-br from-teal-500 via-teal-600 to-indigo-600 p-6 sm:p-10 text-white shadow-xl">
                    {/* Decorative blobs */}
                    <div className="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                    <div className="absolute -bottom-10 -left-10 w-48 h-48 bg-indigo-400/20 rounded-full blur-2xl"></div>

                    <div className="relative z-10">
                        <div className="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm px-3 py-1.5 rounded-full mb-4">
                            <span className="relative flex h-2 w-2">
                                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span className="relative inline-flex rounded-full h-2 w-2 bg-green-400"></span>
                            </span>
                            <span className="text-[10px] font-bold uppercase tracking-widest text-white/90">Student Portal</span>
                        </div>

                        <h1 className="text-2xl sm:text-4xl font-extrabold mb-2 leading-tight">
                            Halo, {session.user.name}! 👋
                        </h1>
                        <p className="text-white/80 text-sm sm:text-base max-w-md mb-6 leading-relaxed">
                            Apa kabar hari ini? Yuk cek mood kamu dan tulis jurnal untuk memantau kesehatan mentalmu.
                        </p>

                        <div className="flex flex-col sm:flex-row gap-3">
                            <Link
                                href="/student/mood"
                                className="inline-flex justify-center items-center gap-2 bg-white text-teal-700 px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-black/10 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 active:scale-95"
                            >
                                😊 Cek Mood
                            </Link>
                            <Link
                                href="/student/journals"
                                className="inline-flex justify-center items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/30 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-white/25 transition-all duration-200 active:scale-95"
                            >
                                📝 Tulis Jurnal
                            </Link>
                        </div>
                    </div>
                </div>

                {/* Stats Grid */}
                <div>
                    <h2 className="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span className="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 text-sm">📊</span>
                        Perjalananmu
                    </h2>
                    <div className="grid grid-cols-2 gap-4">
                        {/* Streak Card */}
                        <div className="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-5 sm:p-6 text-white shadow-lg shadow-orange-200/50">
                            <div className="flex items-center justify-between mb-4">
                                <div className="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-xl sm:text-2xl">🔥</div>
                                <span className="bg-white/20 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Streak</span>
                            </div>
                            <h3 className="text-4xl sm:text-5xl font-black leading-none mb-1">{stats.streak}</h3>
                            <p className="text-orange-100 text-xs sm:text-sm font-medium">Hari berturut-turut</p>
                        </div>

                        {/* Entries Card */}
                        <div className="bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl p-5 sm:p-6 text-white shadow-lg shadow-violet-200/50">
                            <div className="flex items-center justify-between mb-4">
                                <div className="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-xl sm:text-2xl">✍️</div>
                                <span className="bg-white/20 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Jurnal</span>
                            </div>
                            <h3 className="text-4xl sm:text-5xl font-black leading-none mb-1">{stats.totalEntries}</h3>
                            <p className="text-violet-100 text-xs sm:text-sm font-medium">Total ditulis</p>
                        </div>
                    </div>
                </div>

                {/* Wisdom Card */}
                <div>
                    <h2 className="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span className="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600 text-sm">💡</span>
                        Kata Bijak Hari Ini
                    </h2>
                    <div className="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-6 sm:p-8 shadow-md border border-amber-100/50 text-center">
                        <div className="w-14 h-14 bg-white rounded-2xl shadow-md flex items-center justify-center text-3xl mx-auto mb-4">{wisdom.emoji}</div>
                        <h3 className="text-lg font-bold text-gray-800 mb-2">{wisdom.title}</h3>
                        <p className="text-gray-600 text-sm leading-relaxed max-w-md mx-auto">&quot;{wisdom.quote}&quot;</p>
                    </div>
                </div>

                {/* Quick Access */}
                <div>
                    <h2 className="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span className="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center text-teal-600 text-sm">⚡</span>
                        Akses Cepat
                    </h2>
                    <div className="grid grid-cols-2 gap-4">
                        <Link href="/student/mood" className="group bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md hover:border-teal-200 transition-all duration-200">
                            <div className="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">😊</div>
                            <h4 className="font-bold text-gray-800 text-sm mb-1">Mood Check</h4>
                            <p className="text-gray-400 text-xs">Catat suasana hatimu</p>
                        </Link>
                        <Link href="/student/journals" className="group bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md hover:border-indigo-200 transition-all duration-200">
                            <div className="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">📓</div>
                            <h4 className="font-bold text-gray-800 text-sm mb-1">Jurnal</h4>
                            <p className="text-gray-400 text-xs">Tulis cerita harimu</p>
                        </Link>
                    </div>
                </div>

            </div>
        </div>
    );
}
