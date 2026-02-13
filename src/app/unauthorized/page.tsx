import Link from "next/link";

export default function UnauthorizedPage() {
    return (
        <div className="flex min-h-screen flex-col items-center justify-center bg-gray-100 p-4 text-center">
            <div className="rounded-lg bg-white p-8 shadow-lg max-w-md w-full">
                <h1 className="mb-4 text-4xl font-bold text-red-600">403</h1>
                <h2 className="mb-6 text-2xl font-semibold text-gray-800">Access Denied</h2>
                <p className="mb-8 text-gray-600">
                    Sorry, you do not have permission to access this page.
                    Please return to the home page or the dashboard that matches your role.
                </p>
                <div className="space-y-4">
                    <Link
                        href="/"
                        className="inline-block w-full rounded-md bg-teal-600 px-6 py-3 text-white font-medium hover:bg-teal-700 transition duration-200"
                    >
                        Back to Home
                    </Link>
                    <Link
                        href="/login"
                        className="inline-block w-full rounded-md border border-gray-300 bg-white px-6 py-3 text-gray-700 font-medium hover:bg-gray-50 transition duration-200"
                    >
                        Login Again
                    </Link>
                </div>
            </div>
        </div>
    );
}
