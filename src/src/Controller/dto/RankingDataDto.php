<?php
namespace App\Controller\dto;

class RankingDataDto
{
    /** @var array Array of RankingDto objects */
    private $ranking;

    /** @var \DateTime */
    private $rankingLastModified;

    /**
     * RankingDataDto constructor.
     * @param array $ranking
     * @param \DateTime $rankingLastModified
     */
    public function __construct(array $ranking, \DateTime $rankingLastModified = null)
    {
        $this->ranking = $ranking;
        $this->rankingLastModified = $rankingLastModified;
    }

    /**
     * @return array
     */
    public function getRanking(): array
    {
        return $this->ranking;
    }

    /**
     * @return string
     */
    public function getRankingLastModified()
    {
        return $this->rankingLastModified->format('Y-m-d H:i');
    }

}