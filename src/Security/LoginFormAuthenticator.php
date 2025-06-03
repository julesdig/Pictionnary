<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    private const string LOGIN_ROUTE = 'app_login';

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly RequestStack $requestStack,
        private readonly Security $security,
        private readonly UserRepository $userRepository,
        private readonly RouterInterface $router,
    ) {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $this->requestStack->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            if (!$this->forceRedirect($targetPath, $request->getLocale())) {
                return new RedirectResponse($targetPath);
            }
        }
        return new RedirectResponse($this->urlGenerator->generate(
            $this->security->isGranted('ROLE_ADMIN')
                ? 'admin'
                : 'dashboard.index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    private function forceRedirect(string $targetPath, string $locale): bool
    {
        $protectedRoutes = [
            'app_login',
        ];

        $routeUrls = array_map(function ($routeName) use ($locale) {
            return $this->router->generate($routeName, ['_locale' => $locale], UrlGeneratorInterface::RELATIVE_PATH);
        }, $protectedRoutes);

        $path = parse_url($targetPath)['path'] ?? '';
        if ($path === '' || $path === '/') {
            return true;
        }

        foreach ($routeUrls as $routeUrl) {
            if (str_contains($path, $routeUrl)) {
                return false;
            }
        }

        return true;
    }
}
