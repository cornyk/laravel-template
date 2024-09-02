#!/bin/sh

# 判断参数
if [ $# -eq 0 ]; then
    echo "Usage: $0 {dev|start}"
    exit 1
fi

# 计算cpu核心数
case "$(uname)" in
    Darwin)
        # macOS
        cpu_cores=$(sysctl -n machdep.cpu.core_count)
        ;;
    Linux)
        # Linux
        cpu_cores=$(nproc)
        ;;
    *)
        echo "Unsupported OS"
        exit 1
        ;;
esac
# 计算workers数量
workers_count=$((cpu_cores * 4))

# 执行运行命令
case "$1" in
    dev)
        echo "Running in development mode..."
        echo "Workers count: $workers_count"
        exec php artisan octane:start --workers="$workers_count" --watch
        ;;
    start)
        echo "Running in production mode..."
        echo "Workers count: $workers_count"
        exec php artisan octane:start --workers="$workers_count"
        ;;
    *)
        echo "Invalid option: $1"
        echo "Usage: $0 {dev|start}"
        exit 1
        ;;
esac
