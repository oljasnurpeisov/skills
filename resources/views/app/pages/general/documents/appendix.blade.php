<p>
    Данный электронный документ подписан с использованием электронной цифровой подписи.
</p>
<p>
    Для проверки электронного документа перейдите по ссылке: <br />
    <a href="{{ $link }}">{{ $link }}</a>
</p>
<br />
<table cellpadding="4" cellspacing="4">
    <tr>
        <td style="width: 25%">Тип документа</td>
        <td>{{ $type }}</td>
    </tr>
    <tr>
        <td style="width: 25%">Номер документа</td>
        <td>{{ $parent }}</td>
    </tr>
    <tr>
        <td style="width: 25%">Уникальный номер</td>
        <td>{{ $number }}</td>
    </tr>
    <tr>
        <td style="width: 25%">Электронные цифровые подписи</td>
        <td>
            @foreach($signatures as $signature)
                <p>
                    {{ $signature->getCertificate()->legalName ? $signature->getCertificate()->legalName : $signature->getCertificate()->personName }}
                </p>
                <p>Подписано: {{ $signature->getCertificate()->personName }} / {{ $signature->user->position_ru ?: ($signature->user->role ? $signature->user->role->role->name : '') }} /</p>
                <p>Дата подписания: {{ $signature->created_at }}</p>
                <hr />
            @endforeach
        </td>
    </tr>
</table>
<br />
<br />
<table style="border: 0">
    <tr>
        <td style="width: 25%;border: 0">
            <barcode type="QR" class="barcode" error="M" code="{{ $link }}" size="1.4" border="0"/>
        </td>
        <td style="width: 75%; border: 0">
            Осы құжат «Электронды құжат және электрондық цифрлық қолтаңба туралы» Қазақстан Республикасының 2003 жылғы 7 қаңтардағы Заңы 7 бабының 1 тармағына сәйкес қағаз тасығыштағы құжатпен
            маңызы бірдей. <br />
            Данный документ согласно пункту 1 статьи 7 ЗРК от 7 января 2003 года "Об электронном документе и электронной цифровой подписи" равнозначен документу на бумажном носителе.</td>
    </tr>
</table>
