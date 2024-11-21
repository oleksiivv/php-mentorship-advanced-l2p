<?php

namespace Http\Core;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    private string $scheme = '';
    private string $user = '';
    private string|null $password = null;
    private string $host = '';
    private int|null $port = null;
    private string $path = '';
    private string $query = '';
    private string $fragment = '';

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getAuthority(): string
    {
        $authority = $this->host;

        if (!empty($this->user)) {
            $authority = $this->user . ':' . $this->password . '@' . $authority;
        }

        if (!is_null($this->port)) {
            $authority .= ':' . $this->port;
        }
        return $authority;
    }

    public function getUserInfo(): string
    {
        if (empty($this->user)) {
            return '';
        }
        return $this->password ? $this->user . ':' . $this->password : $this->user;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int|null
    {
        return $this->port;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function withScheme($scheme): UriInterface
    {
        $new = clone $this;
        $new->scheme = strtolower($scheme);
        return $new;
    }

    public function withUserInfo($user, $password = null): UriInterface
    {
        $new = clone $this;
        $new->user = $user;
        $new->password = $password;
        return $new;
    }

    public function withHost($host): UriInterface
    {
        $new = clone $this;
        $new->host = strtolower($host);
        return $new;
    }

    public function withPort($port): UriInterface
    {
        $new = clone $this;
        $new->port = $port;
        return $new;
    }

    public function withPath($path): UriInterface
    {
        $new = clone $this;
        $new->path = $path;
        return $new;
    }

    public function withQuery($query): UriInterface
    {
        $new = clone $this;
        $new->query = $query;
        return $new;
    }

    public function withFragment($fragment): UriInterface
    {
        $new = clone $this;
        $new->fragment = $fragment;
        return $new;
    }

    public function __toString(): string
    {
        return $this->scheme . '://' . $this->getAuthority() . $this->path
            . ($this->query ? '?' . $this->query : '')
            . ($this->fragment ? '#' . $this->fragment : '');
    }
}
