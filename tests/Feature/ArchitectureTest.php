test('controllers do not use debugging functions')
    ->expect(['dd', 'dump', 'var_dump', 'die'])
    ->not->toBeUsed();

test('models should be in App\Models namespace')
    ->expect('App\Models')
    ->toBeUsed();