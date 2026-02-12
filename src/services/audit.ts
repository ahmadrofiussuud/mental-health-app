import { db } from "@/lib/db";

interface AuditParams {
    actorId: string;
    targetUserId?: string;
    action: string;
    resourceId?: string;
    details?: any;
}

export async function logAudit(params: AuditParams) {
    try {
        await db.auditLog.create({
            data: {
                actorId: params.actorId,
                targetUserId: params.targetUserId,
                action: params.action,
                resourceId: params.resourceId,
                details: params.details ?? {}
            }
        });
    } catch (e) {
        console.error("Failed to write audit log:", e);
        // Don't throw, just log error so main flow isn't interrupted
    }
}
