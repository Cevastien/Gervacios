{{-- Seating analytics panel — SaaS KPI + chart tokens --}}
<style>
    .sa-stat-card,
    .sa-chart-card,
    .sa-stat-label,
    .sa-stat-value,
    .sa-stat-sub,
    .sa-chart-title,
    .sa-chart-sub,
    .sa-timestamp {
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .sa-stat-card {
        position: relative;
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 2px 0 rgb(15 23 42 / 0.05);
        padding: 16px 18px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        min-height: 108px;
    }

    .sa-stat-card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
    }

    .sa-stat-label {
        font-size: 12px;
        font-weight: 500;
        color: #64748b;
        line-height: 1.35;
        margin: 0;
        padding-right: 4px;
    }

    .sa-stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(15, 23, 42, 0.06);
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 14px;
    }

    .sa-stat-value {
        font-size: 1.875rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.1;
        letter-spacing: -0.02em;
        font-variant-numeric: tabular-nums;
        margin: 0;
    }

    .sa-stat-value-split {
        display: flex;
        align-items: baseline;
        flex-wrap: wrap;
        gap: 0 6px;
    }

    .sa-stat-num-primary {
        font-size: 1.875rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.02em;
        font-variant-numeric: tabular-nums;
    }

    .sa-stat-num-sep {
        font-size: 1.125rem;
        font-weight: 500;
        color: #cbd5e1;
    }

    .sa-stat-num-secondary {
        font-size: 1.25rem;
        font-weight: 600;
        color: #64748b;
        font-variant-numeric: tabular-nums;
    }

    .sa-stat-sub {
        font-size: 12px;
        font-weight: 500;
        color: #64748b;
        margin: 0;
        margin-top: auto;
    }

    .sa-chart-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 2px 0 rgb(15 23 42 / 0.05);
        padding: 18px;
        display: flex;
        flex-direction: column;
    }

    .sa-chart-title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.3;
        margin: 0;
    }

    .sa-chart-title span {
        font-weight: 500;
        color: #64748b;
    }

    .sa-chart-sub {
        font-size: 12px;
        font-weight: 500;
        color: #64748b;
        margin: 6px 0 16px;
    }

    .sa-chart-wrap {
        position: relative;
        height: 260px;
        flex: 1;
    }

    .sa-timestamp {
        font-size: 11px;
        font-weight: 500;
        color: #64748b;
        text-align: right;
    }
</style>
