<?php

namespace Nurmuhammet\Analytics\Http\Middleware;

use Closure;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Nurmuhammet\Analytics\Models\PageView;

class Analytics
{
    public function handle(Request $request, Closure $next)
    {
        $uri = str_replace($request->root(), '', $request->url()) ?: '/';

        $response = $next($request);

        if (in_array($uri, config('analytics.exclude', []))) {
            return $response;
        }

        $agent = new Agent();
        $agent->setUserAgent($request->headers->get('user-agent'));
        $agent->setHttpHeaders($request->headers);

        PageView::create([
            'session' => $request->session()->getId(),
            'uri' => $uri,
            'source' => $request->headers->get('referer'),
            'country' => $agent->languages()[0] ?? 'en-en',
            'browser' => $agent->browser() ?? null,
            'device' => $agent->deviceType(),
            'ip' => $request->ip
        ]);

        return $response;
    }

    protected function input(Request $request): array
    {
        $files = $request->files->all();

        array_walk_recursive($files, function (&$file) {
            $file = [
                'name' => $file->getClientOriginalName(),
                'size' => $file->isFile() ? ($file->getSize() / 1000) . 'KB' : '0',
            ];
        });

        return array_replace_recursive($request->input(), $files);
    }
}
