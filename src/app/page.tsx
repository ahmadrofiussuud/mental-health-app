import Link from "next/link";
import Image from "next/image";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";

export default async function Home() {
  const session = await getServerSession(authOptions);

  return (
    <div className="relative min-h-screen flex flex-col font-sans">
      {/* Background Image with Overlay */}
      <div className="absolute inset-0 z-0">
        <Image
          src="/images/campus-life.png"
          alt="Campus Life"
          fill
          className="object-cover"
          priority
        />
        <div className="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent"></div>
      </div>

      {/* Header */}
      <div className="relative z-10 w-full max-w-7xl mx-auto px-6">
        <header className="flex items-center justify-between py-8">
          <div className="flex items-center gap-2">
            {/* Logo */}
            <div className="flex items-center gap-2">
              <div className="w-10 h-10 relative">
                <Image
                  src="/images/serenity-hub-logo.png"
                  alt="SerenityHub"
                  fill
                  className="object-contain brightness-0 invert"
                />
              </div>
              <h1 className="text-2xl font-bold text-white tracking-wide">
                SerenityHub
              </h1>
            </div>
          </div>
          <nav className="flex gap-4">
            {session ? (
              <Link
                href={
                  session.user.role === "teacher"
                    ? "/teacher/dashboard"
                    : session.user.role === "admin"
                      ? "/admin/dashboard"
                      : "/student/dashboard"
                }
                className="px-5 py-2.5 text-sm font-medium text-white bg-teal-600 rounded-full hover:bg-teal-700 transition duration-300 shadow-md backdrop-blur-sm bg-opacity-90"
              >
                Dashboard
              </Link>
            ) : (
              <>
                <Link
                  href="/login"
                  className="px-5 py-2.5 text-sm font-medium text-gray-200 hover:text-white transition duration-300"
                >
                  Login
                </Link>
                {/* Register link removed as per typical enterprise app flow, or can be added back if public reg is allowed */}
              </>
            )}
          </nav>
        </header>
      </div>

      {/* Hero Content */}
      <main className="relative z-10 flex-grow flex items-center w-full max-w-7xl mx-auto px-6">
        <div className="max-w-2xl pb-20">
          <div className="inline-flex items-center px-3 py-1 rounded-full border border-teal-400/30 bg-teal-900/30 backdrop-blur-md mb-6">
            <span className="flex h-2 w-2 rounded-full bg-teal-400 mr-2 animate-pulse"></span>
            <span className="text-xs font-medium text-teal-100 uppercase tracking-wider">
              Student Mental Health Is Our Priority
            </span>
          </div>

          <h1 className="text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-tight mb-6 drop-shadow-sm">
            A Safe Space for <br />
            <span className="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-emerald-400">
              Mental Growth
            </span>
          </h1>

          <p className="text-lg md:text-xl text-gray-200 mb-10 leading-relaxed max-w-lg">
            SerenityHub helps you understand your emotions and provides a safe
            channel to communicate with your school counselor. You are not alone
            in this journey.
          </p>

          <div className="flex flex-col sm:flex-row gap-4">
            <Link
              href="/student/mood"
              className="group relative px-8 py-4 bg-teal-600 text-white font-semibold rounded-xl shadow-lg shadow-teal-500/40 hover:bg-teal-700 transition-all duration-300 hover:-translate-y-1 overflow-hidden"
            >
              <div className="relative z-10 flex items-center justify-center gap-2">
                <span>Start Daily Check-in</span>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  className="h-5 w-5 transition-transform group-hover:translate-x-1"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path
                    fillRule="evenodd"
                    d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                    clipRule="evenodd"
                  />
                </svg>
              </div>
            </Link>

            <Link
              href="#"
              className="px-8 py-4 bg-white/10 text-white font-medium rounded-xl border border-white/20 backdrop-blur-sm hover:bg-white/20 transition-all duration-300 flex items-center justify-center hover:border-white/40"
            >
              Learn More
            </Link>
          </div>

          {/* Stats / Trust Indicators */}
          <div className="mt-12 flex items-center gap-8 border-t border-white/10 pt-8">
            <div>
              <p className="text-3xl font-bold text-white">500+</p>
              <p className="text-sm text-gray-400">Students Helped</p>
            </div>
            <div>
              <p className="text-3xl font-bold text-white">24/7</p>
              <p className="text-sm text-gray-400">Counseling Access</p>
            </div>
            <div>
              <p className="text-3xl font-bold text-white">100%</p>
              <p className="text-sm text-gray-400">Privacy Guaranteed</p>
            </div>
          </div>
        </div>
      </main>
    </div>
  );
}
