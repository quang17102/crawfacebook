<?php

namespace App\Http\Controllers;

use App\Constant\GlobalConstant;
use App\Models\Link;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function getLinkOrPostIdFromUrl(string $url = '')
    {
        $url = explode('/', $url ?? '');

        return  count($url) ? $url[count($url) - 1] : '';
    }

    public function syncPointToLinkBeforeUpdateLink(string $link_id, string $parent_link_or_post_id)
    {
        if ($parent_link_or_post_id) {
            // prent links
            $parent_child = Link::firstWhere('link_or_post_id', $parent_link_or_post_id);
            // get link
            $link = Link::with([
                'userLinksWithTrashed.user', 'userLinks.user',
                'reactionLinks.reaction'
            ])->firstWhere('id', $link_id);
            $userLinks =  $link->userLinks ?? [];

            // update to point to parent link
            foreach ($userLinks as $userLink) {
                $userLink->update([
                    'link_id' => $parent_child->id,
                ]);
            }
        }
    }

    public function syncPointToLinkBeforeCreateLink(array $data)
    {
        // get link
        $link = Link::with(['userLinks'])->withTrashed()->firstWhere('link_or_post_id', $data['link_or_post_id']);
        if ($link) {
            if ($link->trashed()) {
                $link->update([
                    'comment' => 0,
                    'diff_comment' => 0,
                    'data' => 0,
                    'diff_data' => 0,
                    'reaction' => 0,
                    'diff_reaction' => 0,
                    'note' => '',
                ]);
            }
        } else {
            $link = Link::updateOrCreate(
                [
                    'link_or_post_id' => $data['link_or_post_id']
                ],
                [
                    'link_or_post_id' => $data['link_or_post_id'],
                    'title' =>  $data['title'] ?? '',
                    'is_scan' => $data['is_scan'] ?? '',
                    'type' => $data['type'] ?? '',
                    'delay' => $data['delay'] ?? '',
                    'status' => $data['status'] ?? '',
                    'comment' => 0,
                    'diff_comment' => 0,
                    'data' => 0,
                    'diff_data' => 0,
                    'reaction' => 0,
                    'diff_reaction' => 0,
                    'note' => '',
                ]
            );
        }

        // current
        return $link;

        // old
        // $link = Link::where('link_or_post_id', $data['link_or_post_id'] ?? '')
        //     ->first();

        // return $link && $link->parentLink ? $link->parentLink : Link::firstOrCreate(
        //     ['link_or_post_id' => $data['link_or_post_id']],
        //     [
        //         'title' =>  $data['title'] ?? '',
        //         'is_scan' => $data['is_scan'] ?? '',
        //         'type' => $data['type'] ?? '',
        //         'delay' => $data['delay'] ?? '',
        //         'status' => $data['status'] ?? '',
        //     ]
        // );
    }
}
