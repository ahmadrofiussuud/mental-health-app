import { withAuth } from "next-auth/middleware";
import { NextResponse } from "next/server";

export default withAuth(
    function middleware(req) {
        const token = req.nextauth.token;
        const path = req.nextUrl.pathname;

        // Role-based Redirects
        if (path.startsWith("/teacher") && token?.role !== "TEACHER" && token?.role !== "ADMIN") {
            return NextResponse.redirect(new URL("/unauthorized", req.url));
        }
        if (path.startsWith("/admin") && token?.role !== "ADMIN") {
            return NextResponse.redirect(new URL("/unauthorized", req.url));
        }
        if (path.startsWith("/student") && token?.role !== "STUDENT") {
            // Teachers might access student views via /teacher/student/[id], not /student/dashboard
            // But /student/ routes are for student dashboard
            return NextResponse.redirect(new URL("/unauthorized", req.url));
        }
    },
    {
        callbacks: {
            authorized: ({ token }) => !!token
        }
    }
);

export const config = { matcher: ["/student/:path*", "/teacher/:path*", "/admin/:path*"] };
