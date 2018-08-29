@component('mail::message')

{{$data['desc_1']}} <b> {{$data['desc_name']}} </b> {{$data['desc_2']}}

<table class="table" align="center" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <th align="center">
            Name
        </th>
        <th align="center">
            Access Card
        </th>
        <th align="center">
            Status
        </th>
    </tr>
    <tr>
        <td align="center" style="border-bottom: 1px solid #EDEFF2;">
            {{ $data['access_card_name']}}
        </td>
        <td align="center" style="border-bottom: 1px solid #EDEFF2;">
            {{$data['access_card_no']}}
            
        </td>
        <td align="center" style="border-bottom: 1px solid #EDEFF2;color:{{$data['status_color']}}" >
            <b> {{$data['status_akses']}} </b>
        </td>
    </tr>
</table>

<br>
@component('mail::left_button', ['url'=>$data['url'],'url_data' => $data['url_data'],'url_reject'=>$data['url_reject']])
View Invoice
@endcomponent


<br>
<br>
Thanks,<br>
{{ config('app.name') }}

{{-- Subcopy --}}
@component('mail::subcopy')
If you’re having trouble clicking the "{{ $data['url'] }}" button, copy and paste the URL below
into your web browser: [{{ $data['url'] }}]({{ $data['url'] }})
@endcomponent

@endcomponent