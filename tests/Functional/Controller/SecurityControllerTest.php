<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Repository\UserRepository;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Generator;

class SecurityControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private RouterInterface $router;
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->router = static::getContainer()->get(RouterInterface::class);
    }
    protected function tearDown(): void
    {
        parent::tearDown();
        static::ensureKernelShutdown();
    }
    #[DataProvider('loginProvider')]
    public function testLogin(string $email, string $password, string $expectedResult): void
    {
        $url = $this->router->generate('app_login');
        $this->client->request(Request::METHOD_GET, $url);
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorExists('a[href="'.$this->router->generate('app_register').'"]');
        $this->client->submitForm('Se connecter', [
            'email' => $email,
            'password' => $password,
        ]);
        $this->client->followRedirect();
        $this->assertRouteSame($expectedResult);
        $this->assertResponseStatusCodeSame(200);
    }

    public static function loginProvider(): Generator
    {
        yield 'admin' => [
            'email'=> 'admin@gmail.com',
            'password' => 'admin',
            'expectedResult' => 'admin_dashboard.index',
        ];
        yield 'user' => [
            'email'=> 'user@gmail.com',
            'password' => 'user',
            'expectedResult' => 'dashboard.index'
        ];
    }

    public function testRegister(): void
    {
        $url = $this->router->generate('app_register');
        $this->client->request(Request::METHOD_GET, $url);
        $this->assertResponseStatusCodeSame(200);
    }
}
