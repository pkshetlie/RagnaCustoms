<?php

namespace App\Tests;

use App\Exception\SongServiceEditorNotRecognized;
use App\Exception\SongServiceNoJsonException;
use App\Interface\IDiscordService;
use App\Interface\INotificationService;
use App\Repository\SongRepository;
use App\Service\SongService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SongServiceTest extends TestCase
{
    private ?SongService $songService;

    public function setUp(): void
    {
        $this->songService = new SongService(
            $this->createMock(KernelInterface::class),
            $this->createMock(EntityManagerInterface::class),
            $this->createMock(MailerInterface::class),
            $this->createMock(IDiscordService::class),
            $this->createMock(UrlGeneratorInterface::class),
            $this->createMock(INotificationService::class),
            $this->createMock(SongRepository::class),
            $this->createMock(Security::class),
            'contact@ragnacustoms.com',
            'contact@ragnacustoms.com'
        );
    }

    protected function tearDown(): void
    {
        $this->songService = null;
    }

    #region converteMapDetection
    /** @test */
    public function mapDetectionIsConverted()
    {
        #region json
        $json = '{
          "_version": "2.0.0",
          "_songName": "Black Betty",
          "_songSubName": "",
          "_songAuthorName": "Caravan Palace",
          "_levelAuthorName": "Arganalth (ITG ATB & D.Vogan chart Convert/Restep)",
          "_beatsPerMinute": 122,
          "_shuffle": 0,
          "_shufflePeriod": 0.5,
          "_previewStartTime": 8,
          "_previewDuration": 16,
          "_songApproximativeDuration": 130,
          "_songFilename": "song.ogg",
          "_coverImageFilename": "cover.jpg",
          "_environmentName": "Midgard",
          "_songTimeOffset": 0,
          "_customData": {
            "_contributors": [],
            "_editors": {
              "Edda": {
                "version": "1.0.1"
              },
              "_lastEditedBy": "Edda",
              "ChroMapper": {
                "version": "4.8.4"
              }
            }
          },
          "_difficultyBeatmapSets": [
            {
              "_beatmapCharacteristicName": "Standard",
              "_difficultyBeatmaps": [
                {
                  "_difficulty": "ExpertPlus",
                  "_difficultyRank": 5,
                  "_beatmapFilename": "ExpertPlus.dat",
                  "_noteJumpMovementSpeed": 20,
                  "_noteJumpStartBeatOffset": 0,
                  "_customData": {
                    "_editorOffset": 0,
                    "_editorOldOffset": 0,
                    "_warnings": [],
                    "_information": [],
                    "_suggestions": [],
                    "_requirements": [],
                    "_editorGridSpacing": 2,
                    "_editorGridDivision": 16
                  }
                }
              ]
            }
          ],
          "_explicit": "false"
        }';
        #endregion
        $json = json_decode($json);
        $this->assertEquals(true, $this->songService->checkIsConverted($json));
    }

    /** @test */
    public function mapDetectionEditorUnknown()
    {
        #region json
        $json = '{
          "_version": "1",
          "_songName": "Shut Up and Dance",
          "_songSubName": "",
          "_songAuthorName": "WALK THE MOON ",
          "_levelAuthorName": "VouesolaR",
          "_explicit": "false",
          "_beatsPerMinute": 128.0,
          "_shuffle": 0,
          "_shufflePeriod": 0.5,
          "_previewStartTime": 0,
          "_previewDuration": 0,
          "_songApproximativeDuration": 199,
          "_songFilename": "song.ogg",
          "_coverImageFilename": "cover.jpg",
          "_environmentName": "Asgard",
          "_songTimeOffset": 0,
          "_customData": {
            "_contributors": [],
            "_editors": {
              "PkCustom": {
                "version": "1.2.7"
              },
              "_lastEditedBy": "Edda"
            }
          },
          "_difficultyBeatmapSets": [
            {
              "_beatmapCharacteristicName": "Standard",
              "_difficultyBeatmaps": [
                {
                  "_difficulty": "Easy",
                  "_difficultyRank": 6,
                  "_beatmapFilename": "Easy.dat",
                  "_noteJumpMovementSpeed": 18.0,
                  "_noteJumpStartBeatOffset": 0,
                  "_customData": {
                    "_editorOldOffset": 0,
                    "_editorGridSpacing": 2.0,
                    "_editorGridDivision": 4,
                    "_warnings": [],
                    "_information": [],
                    "_suggestions": [],
                    "_requirements": []
                  }
                }
              ]
            }
          ]
        }';
        #endregion
        $json = json_decode($json);
        $this->expectException(SongServiceEditorNotRecognized::class);
        $this->expectExceptionMessage('PkCustom');
        $this->songService->checkIsConverted($json);
    }

    /** @test */
    public function mapDetectionNoJson()
    {
        #region json
        $json ='';
        #endregion
        $json = json_decode($json);

        $this->expectException(SongServiceNoJsonException::class);
        $this->songService->checkIsConverted($json);
    }

    /** @test */
    public function mapDetectionNoCustomData()
    {
        #region json
        $json = '{
          "_version": "1",
          "_songName": "Shut Up and Dance",
          "_songSubName": "",
          "_songAuthorName": "WALK THE MOON ",
          "_levelAuthorName": "VouesolaR",
          "_explicit": "false",
          "_beatsPerMinute": 128.0,
          "_shuffle": 0,
          "_shufflePeriod": 0.5,
          "_previewStartTime": 0,
          "_previewDuration": 0,
          "_songApproximativeDuration": 199,
          "_songFilename": "song.ogg",
          "_coverImageFilename": "cover.jpg",
          "_environmentName": "Asgard",
          "_songTimeOffset": 0,     
          "_difficultyBeatmapSets": [
            {
              "_beatmapCharacteristicName": "Standard",
              "_difficultyBeatmaps": [
                {
                  "_difficulty": "Easy",
                  "_difficultyRank": 6,
                  "_beatmapFilename": "Easy.dat",
                  "_noteJumpMovementSpeed": 18.0,
                  "_noteJumpStartBeatOffset": 0,
                  "_customData": {
                    "_editorOldOffset": 0,
                    "_editorGridSpacing": 2.0,
                    "_editorGridDivision": 4,
                    "_warnings": [],
                    "_information": [],
                    "_suggestions": [],
                    "_requirements": []
                  }
                }
              ]
            }
          ]
        }';
        #endregion
        $json = json_decode($json);

        $this->assertEquals(false, $this->songService->checkIsConverted($json));
    }

    /** @test */
    public function mapDetectionNoEditor()
    {
        #region json
        $json = '{
          "_version": "1",
          "_songName": "Shut Up and Dance",
          "_songSubName": "",
          "_songAuthorName": "WALK THE MOON ",
          "_levelAuthorName": "VouesolaR",
          "_explicit": "false",
          "_beatsPerMinute": 128.0,
          "_shuffle": 0,
          "_shufflePeriod": 0.5,
          "_previewStartTime": 0,
          "_previewDuration": 0,
          "_songApproximativeDuration": 199,
          "_songFilename": "song.ogg",
          "_coverImageFilename": "cover.jpg",
          "_environmentName": "Asgard",
          "_songTimeOffset": 0,
          "_customData": {
            "_contributors": [],
            "_editors": {          
            }
          },
          "_difficultyBeatmapSets": [
            {
              "_beatmapCharacteristicName": "Standard",
              "_difficultyBeatmaps": [
                {
                  "_difficulty": "Easy",
                  "_difficultyRank": 6,
                  "_beatmapFilename": "Easy.dat",
                  "_noteJumpMovementSpeed": 18.0,
                  "_noteJumpStartBeatOffset": 0,
                  "_customData": {
                    "_editorOldOffset": 0,
                    "_editorGridSpacing": 2.0,
                    "_editorGridDivision": 4,
                    "_warnings": [],
                    "_information": [],
                    "_suggestions": [],
                    "_requirements": []
                  }
                }
              ]
            }
          ]
        }';
        #endregion
        $json = json_decode($json);

        #region json2
        $json2 = '{
          "_version": "1",
          "_songName": "Shut Up and Dance",
          "_songSubName": "",
          "_songAuthorName": "WALK THE MOON ",
          "_levelAuthorName": "VouesolaR",
          "_explicit": "false",
          "_beatsPerMinute": 128.0,
          "_shuffle": 0,
          "_shufflePeriod": 0.5,
          "_previewStartTime": 0,
          "_previewDuration": 0,
          "_songApproximativeDuration": 199,
          "_songFilename": "song.ogg",
          "_coverImageFilename": "cover.jpg",
          "_environmentName": "Asgard",
          "_songTimeOffset": 0,
          "_customData": {
            "_contributors": []         
          },
          "_difficultyBeatmapSets": [
            {
              "_beatmapCharacteristicName": "Standard",
              "_difficultyBeatmaps": [
                {
                  "_difficulty": "Easy",
                  "_difficultyRank": 6,
                  "_beatmapFilename": "Easy.dat",
                  "_noteJumpMovementSpeed": 18.0,
                  "_noteJumpStartBeatOffset": 0,
                  "_customData": {
                    "_editorOldOffset": 0,
                    "_editorGridSpacing": 2.0,
                    "_editorGridDivision": 4,
                    "_warnings": [],
                    "_information": [],
                    "_suggestions": [],
                    "_requirements": []
                  }
                }
              ]
            }
          ]
        }';
        #endregion
        $json2 = json_decode($json2);

        $this->assertEquals(false, $this->songService->checkIsConverted($json));
        $this->assertEquals(false, $this->songService->checkIsConverted($json2));
    }
    #endregion
}
