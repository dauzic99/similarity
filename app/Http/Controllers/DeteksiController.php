<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DeteksiController extends Controller
{
    public function index()
    {
        return view('deteksi');
    }

    public function process(Request $request)
    {
        if ($request->ajax()) {
            $kalimat1 = $request->input('kalimat1');
            $kalimat2 = $request->input('kalimat2');
            $ngram = $request->input('ngram');
            $window = $request->input('window');
            $prima = $request->input('prima');

            // Panggil fungsi winnowing
            $result1 = $this->winnowing($kalimat1, $ngram, $window, $prima);
            $result2 = $this->winnowing($kalimat2, $ngram, $window, $prima);

            // return response()->json([
            //     'result1' => $result1,
            //     'result2' => $result2,
            //     'jaccard' => $this->jaccardSimilarity($result1['fingerprint'], $result2['fingerprint'])
            // ]);

            return View::make('hasildeteksi')
                ->with('kalimat1', $kalimat1)
                ->with('kalimat2', $kalimat2)
                ->with('result1', $result1)
                ->with('result2', $result2)
                ->with('jaccard', $this->jaccardSimilarity($result1['fingerprint'], $result2['fingerprint']))
                ->render();
        }
    }

    function winnowing($text, $k, $w, $prime)
    {
        //menghapus spasi dari text
        $textFiltered = str_replace(' ', '', strtolower($text));


        $ngrams = [];
        $window_hashes = [];
        $fingerprint = [];


        // buat ngrams
        for ($i = 0; $i <= strlen($textFiltered) - $k; $i++) {
            $ngram = substr($textFiltered, $i, $k);
            array_push($ngrams, $ngram);
        }


        // hitung rolling hash dari ngrams
        $rolling_hash = array_map(function ($ngram) use ($prime) {
            return $this->hashValue($ngram, $prime);
        }, $ngrams);


        //masukkan window
        for ($i = 0; $i <= count($rolling_hash) - $w; $i++) {
            $window = [];
            for ($j = 0; $j < $w; $j++) {
                array_push($window, $rolling_hash[$i + $j]);
            }
            array_push($window_hashes, $window);
        }

        //cari fingerprint

        $fingerprint = $this->winnowingFingerprint($window_hashes, $prime);


        // foreach ($ngrams as $index => $ngram) {
        //     $hash = $this->hashValue($ngram, $prime);

        //     if ($index < $w) {
        //         $window_hashes[] = $hash;

        //         if ($index == $w - 1) {
        //             $min_hashes[] = min($window_hashes);
        //         }
        //     } else {
        //         $window_hashes[] = $hash;
        //         array_shift($window_hashes);
        //         $min_hashes[] = min($window_hashes);
        //     }
        // }




        return [
            'ngrams' => $ngrams,
            'rolling_hash' => $rolling_hash,
            'window_hashes' => $window_hashes,
            'fingerprint' => $fingerprint,
        ];
    }

    function winnowingFingerprint($windowHash, $t)
    {
        $fingerprint = [];

        // Cari nilai minimum dari setiap window hash dan simpan sebagai fingerprint
        for ($i = 0; $i < count($windowHash); $i++) {
            $minValue = min($windowHash[$i]);

            // Simpan nilai minimum sebagai fingerprint jika >= t
            if ($minValue >= $t) {
                array_push($fingerprint, $minValue);
            }
        }

        return $fingerprint;
    }

    function hashValue($text, $prime)
    {
        $hash = 0;
        $len = strlen($text);

        for ($i = 0; $i < $len; $i++) {
            $hash += ord($text[$i]) * pow($prime, $len - $i - 1);
        }

        return $hash;
    }

    function jaccardSimilarity($fingerprint1, $fingerprint2)
    {
        // Hitung intersection
        $intersection = array_intersect($fingerprint1, $fingerprint2);

        // Hitung union
        $union = array_merge($fingerprint1, $fingerprint2);

        // Hitung Jaccard Similarity
        $similarity = count($intersection) / (count($union) - count($intersection));

        return [
            'numUnion' => count($union),
            'numIntersection' => count($intersection),
            'jaccard' => $similarity * 100,
        ];
    }
}
