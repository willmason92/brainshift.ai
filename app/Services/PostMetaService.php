<?php

namespace App\Services;

use App\Models\Location;
use App\Models\PostMeta;

class PostMetaService
{
    public function updateInstallerProjectPostMeta($post, $validated)
    {
        if (!empty($validated['location'])) {
            $location = Location::updateOrCreate(
                [
                    'entity_id' => $post->id,
                    'entity_type' => $post::class,
                ],
                array_merge([
                    'entity_id' => $post->id,
                    'entity_type' => $post::class,
                    'name' => '',
                ], json_decode($validated['location'], true))
            );
            if (!empty($location)) {
                PostMeta::updateOrCreate(
                    ['posts_id' => $post->id, 'fields_id' => 2],
                    [
                        'posts_id' => $post->id,
                        'fields_id' => 2,
                        'value' => $location->id,
                    ]
                );
            }
        }

        // Project Materials
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 5],
            [
                'posts_id' => $post->id,
                'fields_id' => 5,
                'value' => json_encode($validated['materials']),
            ]
        );
        // Sector
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 11],
            [
                'posts_id' => $post->id,
                'fields_id' => 11,
                'value' => $validated['sector'],
            ]
        );
        // Project Type
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 3],
            [
                'posts_id' => $post->id,
                'fields_id' => 3,
                'value' => $validated['projectType'],
            ]
        );

        // Size
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 12],
            [
                'posts_id' => $post->id,
                'fields_id' => 12,
                'value' => $validated['sizeLength'],
            ]
        );
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 13],
            [
                'posts_id' => $post->id,
                'fields_id' => 13,
                'value' => $validated['sizeWidth'],
            ]
        );
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 14],
            [
                'posts_id' => $post->id,
                'fields_id' => 14,
                'value' => $validated['sizeUnit'],
            ]
        );
        //Project Finished
        if (!empty($validated['dateComplete'])) {
            PostMeta::updateOrCreate(
                ['posts_id' => $post->id, 'fields_id' => 4],
                [
                    'posts_id' => $post->id,
                    'fields_id' => 4,
                    'value' => $validated['dateComplete'],
                ]
            );
        }
    }

    public function updatePostMeta($post, $validated)
    {
        if (!empty($validated['location'])) {
            $location = Location::updateOrCreate(
                [
                    'entity_id' => $post->id,
                    'entity_type' => $post::class,
                ],
                array_merge([
                    'entity_id' => $post->id,
                    'entity_type' => $post::class,
                    'name' => '',
                ], json_decode($validated['location'], true))
            );
            if (!empty($location)) {
                PostMeta::updateOrCreate(
                    ['posts_id' => $post->id, 'fields_id' => 2],
                    [
                        'posts_id' => $post->id,
                        'fields_id' => 2,
                        'value' => $location->id,
                    ]
                );
            }
        }

        // Project Materials
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 5],
            [
                'posts_id' => $post->id,
                'fields_id' => 5,
                'value' => json_encode($validated['materials']),
            ]
        );

        // Sector
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 11],
            [
                'posts_id' => $post->id,
                'fields_id' => 11,
                'value' => $validated['sector'],
            ]
        );

        // Project Type
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 3],
            [
                'posts_id' => $post->id,
                'fields_id' => 3,
                'value' => $validated['projectType'],
            ]
        );

        // Size
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 12],
            [
                'posts_id' => $post->id,
                'fields_id' => 12,
                'value' => $validated['sizeLength'],
            ]
        );
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 13],
            [
                'posts_id' => $post->id,
                'fields_id' => 13,
                'value' => $validated['sizeWidth'],
            ]
        );
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 14],
            [
                'posts_id' => $post->id,
                'fields_id' => 14,
                'value' => $validated['sizeUnit'],
            ]
        );
        //Project Finished
        if (!empty($validated['dateCompleted'])) {
            PostMeta::updateOrCreate(
                ['posts_id' => $post->id, 'fields_id' => 4],
                [
                    'posts_id' => $post->id,
                    'fields_id' => 4,
                    'value' => $validated['dateCompleted'],
                ]
            );
        }
    }

    /**
     * Method to update the Case Study post meta for Blog Management
     *
     * @param $post
     * @param $validated
     * @return void
     */
    public function updateCaseStudyPostMeta($post, $validated)
    {
        if (!empty($validated['location'])) {
            $location = Location::updateOrCreate(
                [
                    'entity_id' => $post->id,
                    'entity_type' => $post::class,
                ],
                array_merge([
                    'entity_id' => $post->id,
                    'entity_type' => $post::class,
                    'name' => '',
                ], json_decode($validated['location'], true))
            );
            if (!empty($location)) {
                PostMeta::updateOrCreate(
                    ['posts_id' => $post->id, 'fields_id' => 2],
                    [
                        'posts_id' => $post->id,
                        'fields_id' => 2,
                        'value' => $location->id,
                    ]
                );
            }
        }

        // Project Materials
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 5],
            [
                'posts_id' => $post->id,
                'fields_id' => 5,
                'value' => json_encode($validated['materials']),
            ]
        );

        //Project Finished
        if (!empty($validated['projectCompletion'])) {
            PostMeta::updateOrCreate(
                ['posts_id' => $post->id, 'fields_id' => 4],
                [
                    'posts_id' => $post->id,
                    'fields_id' => 4,
                    'value' => $validated['projectCompletion'],
                ]
            );
        }

        // Sector
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 11],
            [
                'posts_id' => $post->id,
                'fields_id' => 11,
                'value' => $validated['sector'][0],
            ]
        );

        // Project Type
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 3],
            [
                'posts_id' => $post->id,
                'fields_id' => 3,
                'value' => $validated['projectType'],
            ]
        );
    }
    /**
     * Method to update the Technical Document post meta for Blog Management
     *
     * @param $post
     * @param $validated
     * @return void
     */
    public function updateTechnicalDocumentPostMeta($post, $validated)
    {
        // Sector
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 11],
            [
                'posts_id' => $post->id,
                'fields_id' => 11,
                'value' => $validated['sector'][0],
            ]
        );

        // Project Type
        PostMeta::updateOrCreate(
            ['posts_id' => $post->id, 'fields_id' => 3],
            [
                'posts_id' => $post->id,
                'fields_id' => 3,
                'value' => $validated['projectType'],
            ]
        );
    }
}
