@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel' || trim($slot) === 'HydroMart')
<img src="https://files.catbox.moe/xnjzaj.ico" class="logo" alt="HydroMart Logo" style="height: 100px; max-width: 100%; border: none;">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
