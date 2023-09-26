<?php

namespace Tests\Feature\Geo;

use App\Contracts\GeoRepositoryInterface;
use App\Http\Requests\Regions\AssignPostcodeRequest;
use App\Http\Requests\Regions\UnAssignPostcodeRequest;
use App\Models\PostcodeMap;
use App\Models\Region;
use App\Models\User;
use Tests\Feature\FeatureTestCase;

class GeoRepositoryTest extends FeatureTestCase
{
    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->geoRepository = $this->app->make(GeoRepositoryInterface::class);
    }

    /**
     * @test
     *
     * @dataProvider postcodeDataProvider
     */
    public function testExtractOutcodeFromPostcode($postcode, $expected): void
    {
        $outcode = $this->geoRepository->extractOutcodeFromPostcode($postcode);
        $this->assertEquals($outcode, $expected);
    }

    /**
     * @return \string[][]
     */
    public function postcodeDataProvider(): array
    {
        return [
            ['sw1a 1aa', 'SW1A'],
            ['SW1A 1AA', 'SW1A'],
            ['SW1A1AA', 'SW1A'],
            [' sW1A 1Aa', 'SW1A'],
            ['SW1A 1AA ', 'SW1A'],
            ['G21RG', 'G2'],
            ['G2 1RG', 'G2'],
            ['g21 RG', 'G2'],
            [' G2 1RG', 'G2'],
        ];
    }

    /**
     * @return void
     */
    public function testReassignPostcode(): void
    {
        $this->userEngland = User::factory()->create();
        $this->userWales = User::factory()->create();

        $this->wales = Region::factory()->create(['region_name' => 'South England', 'user_id' => $this->userWales->id]);
        $this->sengland = Region::factory()->create(['region_name' => 'South England', 'user_id' => $this->userEngland->id]);

        $this->se_code = PostcodeMap::factory()->create(['outcode' => 'SW1A', 'region_id' => $this->sengland->id]);
        $this->w_code = PostcodeMap::factory()->create(['outcode' => 'G2', 'region_id' => $this->wales->id]);

        $this->assertDatabaseHas('postcode_map', $this->w_code->toArray());

        $formRequest = new AssignPostcodeRequest([
            'postcode' => $this->se_code->outcode,
            'region_id' => $this->wales->id,
        ]);

        $this->geoRepository->assignPostcodeToRegion($formRequest);

        $this->assertDatabaseHas('postcode_map', [
            'outcode' => $this->se_code->outcode,
            'region_id' => $this->wales->id,
        ]);
    }

    /**
     * @return void
     */
    public function testUnassignPostcode(): void
    {
        $this->userEngland = User::factory()->create();
        $this->sengland = Region::factory()->create(['region_name' => 'South England', 'user_id' => $this->userEngland->id]);
        $this->se_code = PostcodeMap::factory()->create(['outcode' => 'SW1A', 'region_id' => $this->sengland->id]);

        $this->assertDatabaseHas('postcode_map', $this->se_code->toArray());

        $formRequest = new UnAssignPostcodeRequest([
            'postcode' => $this->se_code->outcode,
        ]);

        $this->geoRepository->unAssignPostcode($formRequest);

        $this->assertDatabaseHas('postcode_map', [
            'outcode' => $this->se_code->outcode,
            'region_id' => null,
        ]);
    }
}
