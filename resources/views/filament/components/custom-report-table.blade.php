<div class="overflow-x-auto">
    <table class="w-full text-sm border border-gray-200 dark:border-gray-700 rounded-lg">
        <thead>
            <tr class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Bulan</th>
                <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Total Shift</th>
                <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="py-3 px-4">{{ $item->month }}</td>
                <td class="py-3 px-4">{{ $item->shift }}</td>
                <td class="py-3 px-4">
                    <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">
                        Aktif
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
