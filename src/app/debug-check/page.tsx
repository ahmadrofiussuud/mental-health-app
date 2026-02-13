export default function DebugPage() {
    return (
        <div className="p-10 text-center">
            <h1 className="text-4xl font-bold text-green-600">Debug Page is Working!</h1>
            <p className="mt-4">Build and Deployment are successful.</p>
            <p className="text-sm text-gray-500">Timestamp: {new Date().toISOString()}</p>
        </div>
    );
}
