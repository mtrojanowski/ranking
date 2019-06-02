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
    public function __construct(array $ranking, \DateTime $rankingLastModified)
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
     * @return \DateTime
     */
    public function getRankingLastModified(): \DateTime
    {
        return $this->rankingLastModified;
    }

}