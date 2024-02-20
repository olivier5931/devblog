<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Debugging;
use App\Entity\Opinion;
use App\Entity\Statistic;
use App\Entity\Tutorial;
use App\Entity\Picture;
use App\Entity\Comment;
use App\Entity\Poll;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager; // remove "common"
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
	private $passwordEncoder;

	private $faker;

	private const USERS = [
		[
			'username' => 'patricia',
			'roles' => [User::ROLE_GITHUB],
			'language' => 'fr',
			'status' => 'enabled'
		],
		[
			'username' => 'julie',
			'roles' => [User::ROLE_GITHUB],
			'language' => 'fr',
			'status' => 'enabled'
		],
		[
			'username' => 'david',
			'roles' => [User::ROLE_GITHUB],
			'language' => 'eng',
			'status' => 'enabled'
		],
		[
			'username' => 'jenny',
			'roles' => [User::ROLE_GITHUB],
			'language' => 'fr',
			'status' => 'disabled'
		],
	];

	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->passwordEncoder = $passwordEncoder;
		$this->faker = Factory::create('fr_FR');
	}

	public function load(ObjectManager $manager)
	{
		foreach (self::USERS as $userFixture) {
			$user = new User();
			$user->setUsername($userFixture['username']);
			$user->setRoles($userFixture['roles']);
			$user->setPassword(
				$this->passwordEncoder->encodePassword(
					$user,
					'12345'
				)
			);
			$user->setLanguage($userFixture['language']);
			$user->setStatus($userFixture['status']);

			$manager->persist($user);

			for ($i = 0; $i < mt_rand(5, 10); $i++) {

				$article = new Article();
				$article->setTitle($this->faker->realText(30));
				$article->setImage('test.jpg');
				$article->setTitleeng($this->faker->realText(30));
				$article->setCategory($this->faker->randomElement(['novice', 'programming', 'framework', 'mixed']));
				$article->setContent($this->faker->realText());
				$article->setContenteng($this->faker->realText());
				$article->setFullcontent($this->faker->realText());
				$article->setFullcontenteng($this->faker->realText());
				$article->setCreatedAt($this->faker->dateTimeThisYear);

				$manager->persist($article);

				for ($j = 0; $j < mt_rand(5, 10); $j++) {

					$comment = new Comment();
					$comment->setUsername($userFixture['username']);
					$comment->setContent($this->faker->realText());
					$comment->setCreatedAt($this->faker->dateTimeThisYear);
					$comment->setLanguage($this->faker->randomElement(['fr', 'eng']));
					$comment->setArticle($article);

					$manager->persist($comment);
				}
			}

			for ($i = 0; $i < mt_rand(5, 10); $i++) {

				$debugging = new Debugging();
				$debugging->setTitle($this->faker->realText(30));
				$debugging->setCategory($this->faker->randomElement(['novice', 'programming', 'framework', 'mixed']));
				$debugging->setSolution($this->faker->realText(30));
				$debugging->setSolutioneng($this->faker->realText(30));
				$debugging->setCreatedAt($this->faker->dateTimeThisYear);

				$manager->persist($debugging);

				for ($j = 0; $j < mt_rand(5, 10); $j++) {

					$comment = new Comment();
					$comment->setUsername($userFixture['username']);
					$comment->setContent($this->faker->realText());
					$comment->setCreatedAt($this->faker->dateTimeThisYear);
					$comment->setLanguage($this->faker->randomElement(['fr', 'eng']));
					$comment->setDebugging($debugging);

					$manager->persist($comment);
				}
			}

			for ($i = 0; $i < mt_rand(5, 10); $i++) {

				$poll = new Poll();
				$poll->setName($this->faker->realText(30));
				$poll->setCategory($this->faker->randomElement(['novice', 'programming', 'framework', 'mixed']));
				$poll->setPublished(1);
				$poll->setClosed(0);
				$poll->setCreatedAt($this->faker->dateTimeThisYear);
				$poll->setTotalVotes(100);

				$manager->persist($poll);

				for ($j = 0; $j < 5; $j++) {

					$opinion = new Opinion();
					$opinion->setName($this->faker->realText(30));
					$opinion->setVotes(20);
					$opinion->setPoll($poll);
					$opinion->setVotesPercentage(($opinion->getVotes() / $poll->getTotalVotes()) * 100);

					$manager->persist($opinion);

					$statistic = new Statistic();
					$statistic->setUser($user);
					$statistic->setPoll($poll);
					$statistic->setOpinion($opinion);

					$manager->persist($statistic);
				}
			}
		}

		$manager->flush();
	}
}
