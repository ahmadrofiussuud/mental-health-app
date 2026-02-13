import { db } from "@/lib/db";

interface AuditParams {
    actorId: string;
    targetUserId?: string;
    action: string;
    resourceId?: string;
    details?: Record<string, unknown>;
}

export async function logAudit(params: AuditParams) {
    try {
        await db.auditLog.create({
            data: {
                actorId: params.actorId,
                targetUserId: params.targetUserId,
                action: params.action,
                resourceId: params.resourceId,
                // eslint-disable-next-line @typescript-eslint/no-explicit-any
                details: (params.details ?? {}) as any
            }
        });
    } catch (e) {
        console.error("Failed to write audit log:", e);
        // Don't throw, just log error so main flow isn't interrupted
    }
}
