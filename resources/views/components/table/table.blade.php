@props([
    'columns' => [],
    'data' => [],
    'key' => 'id',
    'emptyMessage' => 'No data available.',
    'striped' => true,
    'hoverable' => true,
    'bordered' => true,
    'responsive' => true,
    'class' => '',
    'sortable' => false,
    'actions' => null,
])

<?php
$tableId = 'table-' . uniqid();
?>

<div class="overflow-x-auto {{ $class }}" {{ $attributes }}>
    <table class="w-full text-sm" id="{{ $tableId }}">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                @foreach ($columns as $column)
                    <th
                        scope="col"
                        class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider
                            {{ $column['class'] ?? '' }}"
                        style="{{ $column['width'] ? 'width: ' . $column['width'] : '' }}"
                        {{ $column['attributes'] ?? '' }}>
                        {{ $column['label'] ?? ucfirst($column['key'] ?? '') }}
                    </th>
                @endforeach
                @if ($actions)
                    <th scope="col" class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody class="{{ $striped ? 'divide-y divide-gray-200' : '' }} {{ $hoverable ? '' : '' }}">
            @forelse ($data as $row)
                <tr class="{{ $hoverable ? 'hover:bg-gray-50 transition-colors' : '' }} {{ $row->getAttribute('table_row_class') ?? '' }}">
                    @foreach ($columns as $column)
                        <td class="px-4 py-3 text-gray-900 {{ $column['class'] ?? '' }}" {{ $column['attributes'] ?? '' }}>
                            @if ($column['render'] ?? false)
                                {{ $column['render']($row) }}
                            @elseif ($column['component'] ?? false)
                                <x-{{ $column['component'] }} :data="$row" :column="$column" />
                            @else
                                {{ $row->{$column['key']} ?? $column['default'] ?? '' }}
                            @endif
                        </td>
                    @endforeach
                    @if ($actions)
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{ $actions($row) }}
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) + ($actions ? 1 : 0) }}" class="px-4 py-8 text-center text-gray-500">
                        {{ $emptyMessage }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>