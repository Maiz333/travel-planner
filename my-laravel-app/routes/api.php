<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json([
        'message' => 'Backend works!'
    ]);
});

// REGISTER
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Registered successfully',
        'user' => $user,
        'token' => $token,
    ]);
});

// LOGIN
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid login data'
        ], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Logged in successfully',
        'user' => $user,
        'token' => $token,
    ]);
});

// LOGOUT
Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Logged out successfully'
    ]);
})->middleware('auth:sanctum');

// CITIES
Route::get('/cities', function () {
    return DB::table('cities')->get();
});

Route::get('/seed-cities', function () {
    DB::table('cities')->truncate();

    DB::table('cities')->insert([
        ['name' => 'Paris'],
        ['name' => 'London'],
        ['name' => 'Tokyo'],
        ['name' => 'Rome'],
        ['name' => 'Berlin'],
        ['name' => 'Barcelona'],
    ]);

    return 'Cities reset and added';
});

// PLACES
Route::get('/places/{city}', function ($cityId) {
    return DB::table('places')
        ->where('city_id', $cityId)
        ->get();
});

Route::get('/seed-places', function () {
    DB::table('places')->truncate();

    DB::table('places')->insert([
        // PARIS
        [
            'name' => 'Hotel Paris Luxury',
            'type' => 'hotel',
            'city_id' => 1,
            'rating' => 4.8,
            'description' => 'Eleganta viesnīca Parīzes centrā ar moderniem numuriem un skaistu pilsētas skatu.',
            'image_url' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Eiffel Grand Hotel',
            'type' => 'hotel',
            'city_id' => 1,
            'rating' => 4.7,
            'description' => 'Komfortabla viesnīca netālu no Eifeļa torņa, piemērota romantiskam ceļojumam.',
            'image_url' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Montmartre Boutique Hotel',
            'type' => 'hotel',
            'city_id' => 1,
            'rating' => 4.6,
            'description' => 'Neliela dizaina viesnīca Monmartras rajonā ar mājīgu atmosfēru.',
            'image_url' => 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Paris Restaurant',
            'type' => 'restaurant',
            'city_id' => 1,
            'rating' => 4.5,
            'description' => 'Franču restorāns ar klasisku Parīzes virtuvi un patīkamu interjeru.',
            'image_url' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Le Gourmet Paris',
            'type' => 'restaurant',
            'city_id' => 1,
            'rating' => 4.9,
            'description' => 'Augstas klases restorāns ar izsmalcinātu ēdienkarti un profesionālu apkalpošanu.',
            'image_url' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Seine Riverside Cafe',
            'type' => 'restaurant',
            'city_id' => 1,
            'rating' => 4.4,
            'description' => 'Mājīga kafejnīca pie Sēnas upes, piemērota brokastīm un kafijai.',
            'image_url' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Louvre Museum',
            'type' => 'museum',
            'city_id' => 1,
            'rating' => 4.9,
            'description' => 'Viens no slavenākajiem muzejiem pasaulē ar plašu mākslas kolekciju.',
            'image_url' => 'https://images.unsplash.com/photo-1549144511-f099e773c147?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Modern Art Museum Paris',
            'type' => 'museum',
            'city_id' => 1,
            'rating' => 4.6,
            'description' => 'Modernās mākslas muzejs ar laikmetīgām izstādēm un instalācijām.',
            'image_url' => 'https://images.unsplash.com/photo-1518998053901-5348d3961a04?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Paris History Gallery',
            'type' => 'museum',
            'city_id' => 1,
            'rating' => 4.5,
            'description' => 'Vēstures galerija, kas iepazīstina ar Parīzes attīstību un kultūru.',
            'image_url' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=900&q=80'
        ],

        // LONDON
        [
            'name' => 'London Royal Hotel',
            'type' => 'hotel',
            'city_id' => 2,
            'rating' => 4.7,
            'description' => 'Eleganta viesnīca Londonas centrā ar ērtu piekļuvi populārākajiem objektiem.',
            'image_url' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Big Ben Suites',
            'type' => 'hotel',
            'city_id' => 2,
            'rating' => 4.6,
            'description' => 'Moderni apartamenti netālu no Big Ben un Temzas upes.',
            'image_url' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Thames View Hotel',
            'type' => 'hotel',
            'city_id' => 2,
            'rating' => 4.5,
            'description' => 'Viesnīca ar skaistu skatu uz Temzu un ērtu atrašanās vietu.',
            'image_url' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'London Steak House',
            'type' => 'restaurant',
            'city_id' => 2,
            'rating' => 4.6,
            'description' => 'Populārs restorāns gaļas ēdienu cienītājiem.',
            'image_url' => 'https://images.unsplash.com/photo-1552566626-52f8b828add9?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'British Restaurant',
            'type' => 'restaurant',
            'city_id' => 2,
            'rating' => 4.4,
            'description' => 'Restorāns ar tradicionāliem britu ēdieniem un mājīgu atmosfēru.',
            'image_url' => 'https://images.unsplash.com/photo-1514933651103-005eec06c04b?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Royal Tea Room',
            'type' => 'restaurant',
            'city_id' => 2,
            'rating' => 4.7,
            'description' => 'Klasiska tējas istaba ar desertiem un britu tējas tradīcijām.',
            'image_url' => 'https://images.unsplash.com/photo-1521017432531-fbd92d768814?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'British Museum',
            'type' => 'museum',
            'city_id' => 2,
            'rating' => 4.8,
            'description' => 'Liels muzejs ar pasaules vēstures, mākslas un kultūras kolekcijām.',
            'image_url' => 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'London History Museum',
            'type' => 'museum',
            'city_id' => 2,
            'rating' => 4.6,
            'description' => 'Muzejs, kas stāsta par Londonas vēsturi un pilsētas attīstību.',
            'image_url' => 'https://images.unsplash.com/photo-1529655683826-aba9b3e77383?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Science Museum London',
            'type' => 'museum',
            'city_id' => 2,
            'rating' => 4.7,
            'description' => 'Interaktīvs zinātnes muzejs ar tehnoloģiju un izgudrojumu izstādēm.',
            'image_url' => 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?auto=format&fit=crop&w=900&q=80'
        ],

        // TOKYO
        [
            'name' => 'Tokyo Imperial Hotel',
            'type' => 'hotel',
            'city_id' => 3,
            'rating' => 4.9,
            'description' => 'Luksusa viesnīca Tokijā ar augstu servisa līmeni un modernu dizainu.',
            'image_url' => 'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Sakura Hotel',
            'type' => 'hotel',
            'city_id' => 3,
            'rating' => 4.5,
            'description' => 'Mājīga viesnīca ar japāņu stila interjeru un ērtu atrašanās vietu.',
            'image_url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Shibuya Sky Hotel',
            'type' => 'hotel',
            'city_id' => 3,
            'rating' => 4.7,
            'description' => 'Mūsdienīga viesnīca Shibuya rajonā ar skatu uz pilsētas panorāmu.',
            'image_url' => 'https://images.unsplash.com/photo-1560347876-aeef00ee58a1?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Tokyo Sushi Restaurant',
            'type' => 'restaurant',
            'city_id' => 3,
            'rating' => 4.8,
            'description' => 'Autentisks suši restorāns ar svaigām jūras veltēm.',
            'image_url' => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Ramen House Tokyo',
            'type' => 'restaurant',
            'city_id' => 3,
            'rating' => 4.6,
            'description' => 'Populārs ramen restorāns ar tradicionālām japāņu zupām.',
            'image_url' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Shinjuku Noodle Bar',
            'type' => 'restaurant',
            'city_id' => 3,
            'rating' => 4.5,
            'description' => 'Ātrs un garšīgs nūdeļu bārs Shinjuku rajonā.',
            'image_url' => 'https://images.unsplash.com/photo-1555126634-323283e090fa?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Tokyo National Museum',
            'type' => 'museum',
            'city_id' => 3,
            'rating' => 4.8,
            'description' => 'Nacionālais muzejs ar Japānas vēstures un mākslas kolekcijām.',
            'image_url' => 'https://images.unsplash.com/photo-1518998053901-5348d3961a04?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Anime Museum',
            'type' => 'museum',
            'city_id' => 3,
            'rating' => 4.6,
            'description' => 'Muzejs anime un manga kultūras cienītājiem.',
            'image_url' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Samurai Culture Museum',
            'type' => 'museum',
            'city_id' => 3,
            'rating' => 4.7,
            'description' => 'Muzejs par samuraju kultūru, bruņām un Japānas vēsturi.',
            'image_url' => 'https://images.unsplash.com/photo-1545569341-9eb8b30979d9?auto=format&fit=crop&w=900&q=80'
        ],

        // ROME
        [
            'name' => 'Rome Palace Hotel',
            'type' => 'hotel',
            'city_id' => 4,
            'rating' => 4.7,
            'description' => 'Klasiska viesnīca Romas centrā ar elegantu interjeru.',
            'image_url' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Colosseum Suites',
            'type' => 'hotel',
            'city_id' => 4,
            'rating' => 4.6,
            'description' => 'Apartamenti netālu no Kolizeja, piemēroti pilsētas apskatei.',
            'image_url' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Vatican Garden Hotel',
            'type' => 'hotel',
            'city_id' => 4,
            'rating' => 4.5,
            'description' => 'Viesnīca klusā rajonā netālu no Vatikāna.',
            'image_url' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Rome Pizza Restaurant',
            'type' => 'restaurant',
            'city_id' => 4,
            'rating' => 4.7,
            'description' => 'Itāļu restorāns ar tradicionālu picu un pastu.',
            'image_url' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Italian Taste Rome',
            'type' => 'restaurant',
            'city_id' => 4,
            'rating' => 4.8,
            'description' => 'Restorāns ar autentiskiem itāļu ēdieniem un vīnu.',
            'image_url' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Trastevere Pasta House',
            'type' => 'restaurant',
            'city_id' => 4,
            'rating' => 4.6,
            'description' => 'Mājīgs pastas restorāns Trastevere rajonā.',
            'image_url' => 'https://images.unsplash.com/photo-1473093295043-cdd812d0e601?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Colosseum Museum',
            'type' => 'museum',
            'city_id' => 4,
            'rating' => 4.8,
            'description' => 'Vēsturisks objekts un muzejs par Senās Romas laiku.',
            'image_url' => 'https://images.unsplash.com/photo-1552832230-c0197dd311b5?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Ancient Rome Museum',
            'type' => 'museum',
            'city_id' => 4,
            'rating' => 4.6,
            'description' => 'Muzejs par Senās Romas kultūru, arhitektūru un ikdienu.',
            'image_url' => 'https://images.unsplash.com/photo-1529260830199-42c24126f198?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Vatican Museum',
            'type' => 'museum',
            'city_id' => 4,
            'rating' => 4.9,
            'description' => 'Pasaulslavens muzejs ar unikālām mākslas kolekcijām.',
            'image_url' => 'https://images.unsplash.com/photo-1529260830199-42c24126f198?auto=format&fit=crop&w=900&q=80'
        ],

        // BERLIN
        [
            'name' => 'Berlin Central Hotel',
            'type' => 'hotel',
            'city_id' => 5,
            'rating' => 4.5,
            'description' => 'Ērta viesnīca Berlīnes centrā ar labu transporta savienojumu.',
            'image_url' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Berlin Wall Suites',
            'type' => 'hotel',
            'city_id' => 5,
            'rating' => 4.6,
            'description' => 'Moderni numuri netālu no Berlīnes mūra vēsturiskajām vietām.',
            'image_url' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Alexanderplatz Hotel',
            'type' => 'hotel',
            'city_id' => 5,
            'rating' => 4.4,
            'description' => 'Viesnīca netālu no Alexanderplatz laukuma.',
            'image_url' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Berlin German Restaurant',
            'type' => 'restaurant',
            'city_id' => 5,
            'rating' => 4.5,
            'description' => 'Restorāns ar vācu virtuves klasiku un tradicionāliem ēdieniem.',
            'image_url' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Schnitzel House Berlin',
            'type' => 'restaurant',
            'city_id' => 5,
            'rating' => 4.7,
            'description' => 'Populārs restorāns ar šnicelēm un vācu ēdieniem.',
            'image_url' => 'https://images.unsplash.com/photo-1514933651103-005eec06c04b?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Brandenburg Cafe',
            'type' => 'restaurant',
            'city_id' => 5,
            'rating' => 4.4,
            'description' => 'Kafejnīca netālu no Brandenburgas vārtiem.',
            'image_url' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Berlin History Museum',
            'type' => 'museum',
            'city_id' => 5,
            'rating' => 4.6,
            'description' => 'Muzejs par Berlīnes vēsturi un pilsētas attīstību.',
            'image_url' => 'https://images.unsplash.com/photo-1560969184-10fe8719e047?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Technology Museum Berlin',
            'type' => 'museum',
            'city_id' => 5,
            'rating' => 4.7,
            'description' => 'Tehnoloģiju muzejs ar transporta, zinātnes un industrijas izstādēm.',
            'image_url' => 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Berlin Wall Museum',
            'type' => 'museum',
            'city_id' => 5,
            'rating' => 4.8,
            'description' => 'Muzejs par Berlīnes mūra vēsturi un aukstā kara periodu.',
            'image_url' => 'https://images.unsplash.com/photo-1587330979470-3595ac045ab0?auto=format&fit=crop&w=900&q=80'
        ],

        // BARCELONA
        [
            'name' => 'Barcelona Beach Hotel',
            'type' => 'hotel',
            'city_id' => 6,
            'rating' => 4.7,
            'description' => 'Viesnīca pie pludmales ar Vidusjūras skatu.',
            'image_url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Sagrada Hotel',
            'type' => 'hotel',
            'city_id' => 6,
            'rating' => 4.6,
            'description' => 'Viesnīca netālu no Sagrada Familia arhitektūras objekta.',
            'image_url' => 'https://images.unsplash.com/photo-1560347876-aeef00ee58a1?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Gothic Quarter Hotel',
            'type' => 'hotel',
            'city_id' => 6,
            'rating' => 4.5,
            'description' => 'Viesnīca Barselonas gotiskajā kvartālā ar vēsturisku noskaņu.',
            'image_url' => 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Barcelona Tapas Restaurant',
            'type' => 'restaurant',
            'city_id' => 6,
            'rating' => 4.8,
            'description' => 'Restorāns ar tradicionālām tapas uzkodām un spāņu atmosfēru.',
            'image_url' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Seafood Barcelona',
            'type' => 'restaurant',
            'city_id' => 6,
            'rating' => 4.7,
            'description' => 'Jūras velšu restorāns ar svaigiem ēdieniem pie piekrastes.',
            'image_url' => 'https://images.unsplash.com/photo-1552566626-52f8b828add9?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Catalan Taste House',
            'type' => 'restaurant',
            'city_id' => 6,
            'rating' => 4.6,
            'description' => 'Restorāns ar katalāņu virtuves ēdieniem.',
            'image_url' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Barcelona Art Museum',
            'type' => 'museum',
            'city_id' => 6,
            'rating' => 4.6,
            'description' => 'Mākslas muzejs ar Spānijas un Eiropas mākslas darbiem.',
            'image_url' => 'https://images.unsplash.com/photo-1518998053901-5348d3961a04?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Gaudi Museum',
            'type' => 'museum',
            'city_id' => 6,
            'rating' => 4.8,
            'description' => 'Muzejs, kas veltīts Gaudi arhitektūrai un Barselonas dizainam.',
            'image_url' => 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=900&q=80'
        ],
        [
            'name' => 'Picasso Museum Barcelona',
            'type' => 'museum',
            'city_id' => 6,
            'rating' => 4.7,
            'description' => 'Muzejs ar Pablo Pikaso darbu kolekciju.',
            'image_url' => 'https://images.unsplash.com/photo-1539037116277-4db20889f2d4?auto=format&fit=crop&w=900&q=80'
        ],
    ]);

    return 'Places reset and added with details';
});

// SAVE PLAN
Route::post('/plans', function (Request $request) {
    DB::table('plans')->insert([
        'user_id' => $request->user()->id,
        'title' => $request->title,
        'places' => json_encode($request->places),
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return ['message' => 'Trip plan saved'];
})->middleware('auth:sanctum');

// GET MY PLANS
Route::get('/my-plans', function (Request $request) {
    return DB::table('plans')
        ->where('user_id', $request->user()->id)
        ->latest()
        ->get();
})->middleware('auth:sanctum');

// DELETE PLAN
Route::delete('/plans/{id}', function ($id, Request $request) {
    DB::table('plans')
        ->where('id', $id)
        ->where('user_id', $request->user()->id)
        ->delete();

    return ['message' => 'Plan deleted'];
})->middleware('auth:sanctum');