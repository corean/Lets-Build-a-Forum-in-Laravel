# Let's Build A Forum with Laravel and TDD
http://laracasts.com/series/lets-build-a-forum-with-laravel

참고 코드
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
$channel->exists /* Model의 결과값이 있는지*/
$channel->exists() /* Model의 Instance가 있는지 */
```
```php        
\View::composer('*', function ($view) {
      $view->with('channels', Channel::all());
});
```
테스트 관련 메소드

- Feature
    - 기능이 잘 구현되었는지
    - `$this->get(), $this->post()` : Tests\TestCase를 상속받아 get 이나 post 처리
    - `assertSee($string)` : assert(주장하다) `$stirng`을 볼수 있는지
    - `$this->be(), $this->actionAs()` : 로그인한 유저
    - `$this->expectException()` : 해당 예외가 발생하는지
    - `$this→assertSessionHasErrors('title')` : validation error check
- Unit
    - 해당 모델인지, 관계는 잘 맺어져있는지
    - `assertInstanceOf($instance)` : 해당 클래스의 인스턴스인지
    - `assertCount(int, $mixed)` : 해당 $mixed의 갯수가 int 개수인지 비교
    - `assertTrue($channel→threads→contains($thread)` : 관계 모델일때 해당 데이타가 있는지

phpstorm

- option + space : 빠른 팝업 메뉴
- command + F12 : 파일 구조 (method list)

추상클래스를 사용하는 이유는 인스턴스를 만들지 않을 부모 클래스라는 의미.
추상메소드가 있다면 반드시 구현해야 한다.

ep.15 refactoring - filter를 통한 리펙토링 (중요)



ep 32 `<reply v-bind:attributes="{{ $reply }}" inline-template v-cloak>`를 통해 라라벨 객체를 js로 넘기는것.

vue 파일내에서 다른 vue 파일 호출 `flash('updated!')

```javascript
//전용으로 만들고 호출했다
window.flash = function(message) {
    window.events.$emit('flash', message);
};
```



`

---

2018-12-02 ~ep. 152
018-12-04 ep. 16
