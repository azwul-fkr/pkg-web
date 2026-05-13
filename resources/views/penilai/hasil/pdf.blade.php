<h1>Laporan Penilaian Guru</h1>

<table width="100%" border="1" cellspacing="0" cellpadding="8">

    <tr>
        <th>Ranking</th>
        <th>Guru</th>
        <th>Periode</th>
        <th>Nilai Akhir</th>
    </tr>

    @foreach($results as $index => $r)

    <tr>

        <td>{{ $index + 1 }}</td>

        <td>{{ $r['guru'] }}</td>

        <td>{{ $r['periode'] }}</td>

        <td>
            {{ number_format($r['nilai_akhir'], 2) }}
        </td>

    </tr>

    @endforeach

</table>