# Let's Build A Forum with Laravel and TDD
http://laracasts.com/series/lets-build-a-forum-with-laravel

모델과 관련된 내용(CRUD)들은 TDD로 진행함.


## 테스트 

- Feature
    - 기능이 잘 구현되었는지
    - `$this->get(), $this->post()` : Tests\TestCase를 상속받아 get 이나 post 처리
    - `assertSee($string)` : assert(주장하다) `$stirng`을 볼수 있는지
    - `assertCount($count, $check)` : `$check`값이 `$count`인지
    - `$this->be(), $this->actionAs()` : 로그인한 유저
    - `$this->expectException()` : 해당 예외가 발생하는지
    - `$this→assertSessionHasErrors('title')` : validation error check
    - `assertDatabaseHas($table, array)` : $table 내용에 array의 값들이 있는지

- Unit
    - 해당 모델인지, 관계는 잘 맺어져있는지
    - `assertInstanceOf($instance)` : 해당 클래스의 인스턴스인지
    - `assertCount(int, $mixed)` : 해당 $mixed의 갯수가 int 개수인지 비교
    - `assertTrue($channel→threads→contains($thread)` : 관계 모델일때 해당 데이타가 있는지


참고코드

```php
/* post 처리후 이동페이지 검사 */
$response = $this->post('/threads', $thread->toArray());
$this->get($response->headers->get('location'));
```

```php        
//app/Exceptions/Handler.php

/* 테스트시 전체 html rendering 하지 않게 */
if (app()->environment() === 'testing') throw $exception;
```

```php        
//tests/Feature/ReadThreadsTest.php

/* __construt() 와 같음. 부도 클래스의 상속받아 확장 */
protected function setUp()
{
    parent::setUp();
    $this->thread = factory(Thread::class)->create();
    }
```


```php        
\View::composer('*', function ($view) {
      $view->with('channels', Channel::all());
});
```



## PHPStorm

- option + space : 빠른 팝업 메뉴
- command + F12 : 파일 구조 (method list)



## PHP

추상클래스를 사용하는 이유는 인스턴스를 만들지 않을 부모 클래스라는 의미.
추상메소드가 있다면 반드시 구현해야 한다.



## Vue

vue 파일내에서 다른 vue 파일내 함수 호출 `flash('updated!')

```javascript
//전용으로 만들고 호출했다
window.flash = function(message) {
    window.events.$emit('flash', message);
};
```

vue 전역 함수는 `Vue.prototype.authroize = function() {}`으로 선언. 호출은 `this.authorize()`

```vue
<reply :attributes="{{ $reply }}" inline-template v-cloak>
```

`:attributes`로 라라벨의 $reply(Model Reply)을 vue로 넘겨줌.
`inline-template`으로 `<reply></reply>`태그안에 내용들로 `<template>`를 대체. blade내에서 laravel의 변수를 보낼수 있음.
v-cloak 으로 깜빡임 방지 `[v-cloak] { display:none; }` 

`this.$emit('deleted', this.data.id)`로 부모한테 이벤트를 보낼때는 1단계만. 받는 것은 `@deleted="remove(index)"`



## Model

```php        
$channel->exists /* Model의 결과값이 있는지*/
$channel->exists() /* Model의 Instance가 있는지 */
```

```php
protected $guarded = []; /* 대량할당시 `$guarded`로 모두 fillable() 처리 */

/* $with`로 관계모델도 같이 보내줌 (blade내 json 데이타로 보낼때도 유용) */
protected $with = ['owner', 'favorites']; 

/* $appends`는 모델의 attribute를 추가해줌. 예: 합계, 등록여부 확인용 */
protected $appends = ['favoritesCount', 'isFavorited']; 
```

Model Boot Method

```php
//General Model
protected static function boot()
{
    parent::boot(); //글로벌 스코프 확장
    static::addGlobalScope('replies_count', function (Builder $builder) {
        $builder->withCount('replies');
    });
    //포럼글 삭제시 댓글도 삭제
    static::deleting(function ($thread) {
        $thread->replies->each->delete(); //Activity 모델도 같이 업데이트
    });
}
```

```php
//trait
trait Favoritable
{
    protected static function bootFavoritable() {
        static::deleting(function($model) {
            $model->favorites->each->delete(); //Activity 모델도 같이 업데이트
        });
    }
    ...
}
```



---

ep.15 refactoring - filter를 통한 리펙토링 (중요)

---

2018-12-02 ~ep. 152
2018-12-04 ep. 16

