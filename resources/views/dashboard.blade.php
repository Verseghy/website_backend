@extends(backpack_view('blank'))

@php
    $widgets['before_content'][] = [
        'type' => 'card',
        'wrapperClass' => 'col-12', // optional
        'class' => 'card bg-light text-black', // optional
        'content' => [
            'header' => 'Képek',
            'body' => '
            <p>
                Kérjük, hogy a képek egyenként ne legyenek nagyobbak, mint 60kB. Javasoljuk a <a href="https://squoosh.app/">https://squoosh.app/</a> alkalmazás használatát.
                Ebben az eszközben ajánlott a képet átméretezni (jobb alsó sarokban a Resize bepipálásával) úgy, hogy az legfeljebb 1000 pixel széles legyen.
                Ezek után a Compress részben válasszuk ki a "MozJPEG"-et (alapértelmezetten ez van) és a quality változatásával tudunk változtatni a kép minőségén.
                Eközben figyeljük, hogy legalul a méret ne lépje túl a 60kB méretet.
                Ha végeztünk az optimalizált képet le tudjuk menteni a jobb alsó sarokban lévő kék mentés gombbal
            </p>
            <p>
                Az indexképeket erősen javasoljuk úgy választani, hogy azok fekvő képek legyenek.
                Az oldal megjeleníti az álló képeket is, de csak úgy, hogy levágja azt a részt ami kilóg a rendelkezésre álló helyéből.
            </p>
            ',
        ]
    ];
@endphp

@section('content')
@endsection