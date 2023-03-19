@extends('template')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Deteksi</h1>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Deteksi</h4>
                        </div>
                        <form action="{{ route('deteksi.process') }}" method="POST" id="form-deteksi">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Kalimat 1</label>
                                            <textarea name="kalimat1" id="kalimat1" cols="30" rows="10" style="height: 150px" class="form-control"
                                                required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Kalimat 2</label>
                                            <textarea name="kalimat2" id="kalimat2" cols="30" rows="10" style="height: 150px" class="form-control"
                                                required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">N-Gram</label>
                                            <input type="number" name="ngram" id="ngram" class="form-control"
                                                min="1" max="5" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Window</label>
                                            <input type="number" name="window" id="window" class="form-control"
                                                min="1" max="5" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Bilangan Prima</label>
                                            <select name="prima" id="prima" class="form-control" required>
                                                @for ($i = 1; $i <= 100; $i++)
                                                    @php
                                                        $is_prime = true;
                                                    @endphp
                                                    @for ($j = 2; $j < $i; $j++)
                                                        @if ($i % $j == 0)
                                                            @php
                                                                $is_prime = false;
                                                                break;
                                                            @endphp
                                                        @endif
                                                    @endfor
                                                    @if ($is_prime)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endif
                                                @endfor
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Hasil</h4>
                        </div>

                        <div class="card-body" id="response">

                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection



@push('scripts')
    <script>
        $(document).ready(function() {
            $('#form-deteksi').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                var url = $(this).attr('action');
                var method = $(this).attr('method');

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    success: function(response) {
                        // console.log(response);
                        $('#response').html(response);
                    },
                    error: function(xhr) {
                        // lakukan sesuatu jika terjadi error saat mengirimkan form
                    }
                });
            });
        });
    </script>
@endpush
