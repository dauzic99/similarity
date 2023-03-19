<div class="row">
    <div class="col-lg-12">
        <Strong>Kalimat Awal</Strong>
    </div>

    <div class="col-lg-6">
        Kalimat 1 = {{ $kalimat1 }}
    </div>
    <div class="col-lg-6">
        Kalimat 2 = {{ $kalimat2 }}
    </div>
</div>
<hr>
<div class="row">
    <div class="col-lg-12">
        <Strong>N-Gram</Strong>
    </div>

    <div class="col-lg-6">
        N-Gram 1 = {{ implode(' ', $result1['ngrams']) }}
    </div>
    <div class="col-lg-6">
        N-Gram 2 = {{ implode(' ', $result2['ngrams']) }}
    </div>
</div>
<hr>
<div class="row">
    <div class="col-lg-12">
        <Strong>Rolling Hash</Strong>
    </div>

    <div class="col-lg-6">
        Rolling Hash 1 = {{ implode(' ', $result1['rolling_hash']) }}
    </div>
    <div class="col-lg-6">
        Rolling Hash 2 = {{ implode(' ', $result2['rolling_hash']) }}
    </div>
</div>
<hr>

<div class="row">
    <div class="col-lg-12">
        <Strong>Window</Strong>
    </div>

    <div class="col-lg-6">
        Window 1

        @foreach ($result1['window_hashes'] as $windows)
            W-{{ $loop->iteration }} : {@foreach ($windows as $window)
                {{ $window }}
            @endforeach},&nbsp;
        @endforeach
    </div>
    <div class="col-lg-6">
        Window 2

        @foreach ($result2['window_hashes'] as $windows)
            W-{{ $loop->iteration }} : {@foreach ($windows as $window)
                {{ $window }}
            @endforeach},&nbsp;
        @endforeach
    </div>
</div>
<hr>

<div class="row">
    <div class="col-lg-12">
        <Strong>Fingerprint</Strong>
    </div>

    <div class="col-lg-6">
        <p>Fingerprint Kalimat 1</p>
        [
        @foreach ($result1['fingerprint'] as $fingerprint)
            {{ $fingerprint }}&nbsp;
        @endforeach
        ]

    </div>
    <div class="col-lg-6">
        <p>Fingerprint Kalimat 2</p>

        [ @foreach ($result2['fingerprint'] as $fingerprint)
            {{ $fingerprint }}&nbsp;
        @endforeach]
    </div>
</div>
<hr>
<div class="row">
    <div class="col-lg-12">
        <strong>Perhitungan Jaccard</strong>
        <br>
        <p>Jumlah Fingerprint Kalimat 1 = {{ count($result1['fingerprint']) }}</p>
        <p>Jumlah Fingerprint Kalimat 2 = {{ count($result2['fingerprint']) }}</p>
        <p>Union (Gabungan) Fingerprint 1 dan 2 = {{ $jaccard['numUnion'] }}</p>
        <p>Intersection(Fingerprint yang sama) = {{ $jaccard['numIntersection'] }}</p>
        <p>(Union - Intersection) = {{ $jaccard['numUnion'] - $jaccard['numIntersection'] }}</p>
        <p>Presentase Plagiarisme = (Intersection / (Union - Intersection))*100 <br>
            ({{ $jaccard['numIntersection'] }}/{{ $jaccard['numUnion'] - $jaccard['numIntersection'] }}) * 100 =
            {{ $jaccard['jaccard'] }}%</p>
    </div>

</div>
