import { PrismaClient } from "@prisma/client";
import { hash } from "bcryptjs";
import "dotenv/config";

const prisma = new PrismaClient({});

async function main() {
    const password = await hash("password", 12);

    // Admin
    const admin = await prisma.user.upsert({
        where: { email: "admin@example.com" },
        update: {},
        create: {
            email: "admin@example.com",
            name: "Admin User",
            password,
            role: "ADMIN"
        }
    });
    console.log({ admin });

    // Teacher
    const teacher = await prisma.user.upsert({
        where: { email: "teacher@example.com" },
        update: {},
        create: {
            email: "teacher@example.com",
            name: "Bu Guru",
            password,
            role: "TEACHER"
        }
    });
    console.log({ teacher });

    // Class
    // Find existing class or create
    const existingClass = await prisma.class.findFirst({ where: { teacherId: teacher.id } });
    let kelasId = existingClass?.id;

    if (!kelasId) {
        const kelas = await prisma.class.create({
            data: {
                name: "Kelas 12 IPS 1",
                teacherId: teacher.id
            }
        });
        kelasId = kelas.id;
        console.log({ kelas });
    }

    // Student
    const student = await prisma.user.upsert({
        where: { email: "student@example.com" },
        update: {},
        create: {
            email: "student@example.com",
            name: "Budi Santoso",
            password,
            role: "STUDENT",
            classId: kelasId
        }
    });
    console.log({ student });

    // Create some journals
    await prisma.journal.createMany({
        data: [
            { userId: student.id, title: "Hari yang berat", content: "Saya merasa sangat lelah hari ini. Tidak ada semangat.", mood: "SAD" },
            { userId: student.id, title: "Lumayan", content: "Hari ini biasa saja.", mood: "NEUTRAL" },
            { userId: student.id, title: "Senang sekali", content: "Dapat nilai bagus ujian matematika!", mood: "HAPPY" }
        ]
    });

    console.log("Seeding done");
}

main()
    .then(async () => await prisma.$disconnect())
    .catch(async (e) => {
        console.error(e);
        await prisma.$disconnect();
        process.exit(1);
    });
