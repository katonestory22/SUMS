<table>
    <thead>
        <tr>
            @if ($showType)
                <th style="width:110px;">Type</th>
            @endif
            <th>Subject</th>
            <th style="width:100px;">Field</th>
            <th style="width:130px;">Old Value</th>
            <th style="width:130px;">New Value</th>
            <th style="width:180px;">Reason</th>
            <th style="width:120px;">Edited By</th>
            <th style="width:100px;">Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse($rows as $row)
            <tr>
                @if ($showType)
                    <td>
                        @php
                            $typeColors = [
                                'Project' => ['#eff6ff', '#1d4ed8'],
                                'Expense' => ['#fef2f2', '#dc2626'],
                                'Phase' => ['#f0fdf4', '#15803d'],
                                'Activity' => ['#fdf4ff', '#7e22ce'],
                                'Company Expense' => ['#fef3c7', '#92400e'],
                            ];
                            $tc = $typeColors[$row['audit_type']] ?? ['#f3f4f6', '#374151'];
                        @endphp
                        <span
                            style="display:inline-block; padding:3px 9px; border-radius:20px;
                                     font-size:11px; font-weight:600;
                                     background:{{ $tc[0] }}; color:{{ $tc[1] }};">
                            {{ $row['audit_type'] }}
                        </span>
                    </td>
                @endif

                <td>
                    <div class="subject-name">{{ $row['subject'] }}</div>
                    @if (!empty($row['audit_type']))
                        <div class="subject-sub">{{ $row['audit_type'] }}</div>
                    @endif
                </td>

                <td><span class="field-badge">{{ $row['field'] }}</span></td>

                <td><span class="old-val">{{ $row['old'] ?: '—' }}</span></td>

                <td><span class="new-val">{{ $row['new'] ?: '—' }}</span></td>

                <td><span class="reason-text">{{ $row['reason'] ?: '—' }}</span></td>

                <td>
                    <div class="editor-name">{{ $row['editor_name'] }}</div>
                </td>

                <td>
                    <div class="edit-date">
                        {{ \Carbon\Carbon::parse($row['audit_date'])->format('d M Y') }}
                    </div>
                    <div class="edit-date">
                        {{ \Carbon\Carbon::parse($row['audit_date'])->format('H:i') }}
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ $showType ? 8 : 7 }}" class="empty-state">
                    No changes recorded
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
