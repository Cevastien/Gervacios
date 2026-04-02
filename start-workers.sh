#!/usr/bin/env bash
#
# =============================================================================
# start-workers.sh — Laravel queue worker + scheduler (one terminal)
# =============================================================================
#
# WHAT THIS RUNS
#   • php artisan queue:work  — processes jobs in the `jobs` table (SMS, etc.)
#   • php artisan schedule:work — runs scheduled tasks from routes/console.php
#
# REQUIREMENTS
#   • Project root = directory containing `artisan` (this script should live there).
#   • `php` on your PATH (Laragon: usually already true in their terminal).
#   • A POSIX shell with job control: Bash on Linux/macOS/WSL or Git Bash on Windows.
#
# HOW TO RUN (Linux / macOS / WSL / Git Bash)
#   cd /path/to/this/project
#   chmod +x start-workers.sh
#   ./start-workers.sh
#
# WINDOWS (Laragon) — Git Bash
#   Right-click project folder → "Git Bash Here", then:
#     chmod +x start-workers.sh
#     ./start-workers.sh
#   If you only have PowerShell/CMD, open TWO terminals instead and run separately:
#     php artisan queue:work
#     php artisan schedule:work
#
# HOW TO STOP
#   Press Ctrl+C — the script stops both child processes.
#
# PRODUCTION NOTE
#   For servers, prefer systemd, Supervisor, Laravel Forge, or Docker (Sail ships
#   supervisord under vendor/laravel/sail). This script is for local/simple use.
#
# =============================================================================

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ROOT"

QUEUE_PID=""
SCHED_PID=""

cleanup() {
  if [[ -n "${QUEUE_PID}" ]] && kill -0 "${QUEUE_PID}" 2>/dev/null; then
    kill "${QUEUE_PID}" 2>/dev/null || true
  fi
  if [[ -n "${SCHED_PID}" ]] && kill -0 "${SCHED_PID}" 2>/dev/null; then
    kill "${SCHED_PID}" 2>/dev/null || true
  fi
}

trap cleanup EXIT INT TERM

php artisan queue:work &
QUEUE_PID=$!

php artisan schedule:work &
SCHED_PID=$!

echo "queue:work PID ${QUEUE_PID}, schedule:work PID ${SCHED_PID} — Ctrl+C to stop both."
wait
