<?php
namespace App\Controller\dto;

class RankingDataDto
{
    /** @var array Array of RankingDto objects */
    private $ranking;

    /** @var \DateTime */
    private $rankingLastModified;

    /** @var string */
    private $rankingTitle;

    /**
     * RankingDataDto constructor.
     * @param array $ranking
     * @param \DateTime $rankingLastModified
     * @param string $rankingTitle
     */
    public function __construct(array $ranking, ?\DateTime $rankingLastModified, string $rankingTitle)
    {
        $this->ranking = $ranking;
        $this->rankingLastModified = $rankingLastModified;
        $this->rankingTitle = $rankingTitle;
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
        return $this->rankingLastModified != null ? $this->rankingLastModified->format('Y-m-d H:i') : "";
    }

    /**
     * @return string
     */
    public function getRankingTitle(): string
    {
        return $this->rankingTitle;
    }
}
