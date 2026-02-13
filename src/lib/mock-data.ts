export const MOCK_USERS = [
    {
        id: "admin-1",
        name: "Admin User",
        email: "admin@example.com",
        password: "password", // In real mock, we compare hashing, but for simple mock we can just check string
        role: "ADMIN",
        image: null,
    },
    {
        id: "teacher-1",
        name: "Ibu Guru Siti",
        email: "teacher@example.com",
        password: "password",
        role: "TEACHER",
        image: "https://ui-avatars.com/api/?name=Siti&background=random",
    },
    {
        id: "student-1",
        name: "Budi Santoso",
        email: "student@example.com",
        password: "password",
        role: "STUDENT",
        image: "https://ui-avatars.com/api/?name=Budi&background=random",
    },
];

export const MOCK_MOODS = [
    {
        id: "mood-1",
        userId: "student-1",
        mood: "HAPPY",
        note: "Belajar coding sangat menyenangkan!",
        createdAt: new Date().toISOString(),
    },
    {
        id: "mood-2",
        userId: "student-1",
        mood: "CALM",
        note: "Istirahat siang yang tenang.",
        createdAt: new Date(Date.now() - 86400000).toISOString(), // Yesterday
    },
];

export const MOCK_JOURNALS = [
    {
        id: "journal-1",
        userId: "student-1",
        title: "Hari Pertama Sekolah",
        content: "Hari ini sangat seru, aku bertemu banyak teman baru.",
        mood: "HAPPY",
        analysis: "Siswa menunjukkan adaptasi sosial yang positif.",
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString(),
    },
    {
        id: "journal-2",
        userId: "student-1",
        title: "Sedikit Cemas",
        content: "Besok ada ujian matematika, aku takut tidak bisa mengerjakannya.",
        mood: "ANXIOUS",
        analysis: "Indikasi kecemasan akademis perlu dukungan belajar.",
        createdAt: new Date(Date.now() - 172800000).toISOString(), // 2 days ago
        updatedAt: new Date(Date.now() - 172800000).toISOString(),
    },
];
