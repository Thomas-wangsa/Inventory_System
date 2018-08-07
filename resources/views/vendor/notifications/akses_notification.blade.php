@component('mail::message')

# Hi {{$data['to']}}

<b> {{$data['from']}} </b> {{$data['desc']}} dengan data sebagai berikut


<table class="table" align="center" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <th align="center">
            Nama Lengkap
        </th>
        <th align="center">
            Email
        </th>
        <th align="center">
            Keterangan
        </th>
        <th align="center">
            Attachment
        </th>
    </tr>
    <tr>
        <td align="center" style="border-bottom: 1px solid #EDEFF2;">
            {{ $data['nama_user']}}
        </td>
        <td align="center" style="border-bottom: 1px solid #EDEFF2;">
            {{ $data['email']}}
        </td>
        <td align="center" style="border-bottom: 1px solid #EDEFF2;">
            {{$data['comment']}}
            
        </td>
        <a href="{{$data['url']}}{{$data['attachment']}}">
            Attachment
        </a>
    </tr>
</table>

<br>
@component('mail::left_button', ['url'=>$data['url'],'url1' => $data['url1'],'url2'=>$data['url2']])
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