In this tutorial I will explain, how to combine AND and OR in a CriteriaCompo.

Explanation of problem:
I want to filter my data from table 'Repositories' by following params:

    *'repo_user' is equal to my variable $dirName
    *'repo_status' should be 2 or should be 3
    *'repo_prerelease' should be 1 or 'repo_release' should be 1
    
From perspective of the query it must be solve like this:

        ('repo_user' = $dirName) AND ('repo_status' = 2 OR 'repo_status' = 3) AND ('repo_prerelease' = 1 OR 'repo_release' = 1)

If you are using CriteriaCompo and combine all criterias in this way:

        $crRepositories = new \CriteriaCompo();
        $crRepositories->add(new \Criteria('repo_user', $dirName));
        $crRepositories->add(new \Criteria('repo_status', 2));
        $crRepositories->add(new \Criteria('repo_status', 3), 'OR');
        $crRepositories->add(new \Criteria('repo_prerelease', 1) );
        $crRepositories->add(new \Criteria('repo_release', 1), 'OR');
        $repositoriesAll = $repositoriesHandler->getAll($crRepositories);

then XOOPS creates following query:

        SELECT * FROM `wggithub_repositories` WHERE (`repo_user` = 'XoopsModulesArchive' AND `repo_status` = '2' OR `repo_status` = '3' AND `repo_prerelease` = '1' OR `repo_release` = '1')

but as the parentheses are missing this query will not give the result you want.

Therefore the question is, how can we force XOOPS to add the necessary parentheses?

The solution is to create a separate CriteriaCompo for each block and combine them in a new CriteriaCompo

        //first block/parentheses
        $crRepo1 = new \CriteriaCompo();
        $crRepo1->add(new \Criteria('repo_user', $dirName));
        
        //second
        $crRepo2 = new \CriteriaCompo();
        $crRepo2->add(new \Criteria('repo_status', Constants::STATUS_UPDATED));
        $crRepo2->add(new \Criteria('repo_status', Constants::STATUS_UPTODATE), 'OR');
        
        //third
        $crRepo3 = new \CriteriaCompo();
        $crRepo3->add(new \Criteria('repo_prerelease', 1) );
        $crRepo3->add(new \Criteria('repo_release', 1), 'OR');
        
        //final combination
        $crRepoFinal = new \CriteriaCompo();
        $crRepoFinal->add($crRepo1);
        $crRepoFinal->add($crRepo2);
        $crRepoFinal->add($crRepo3);
        
        //get data
        $repositoriesAll = $repositoriesHandler->getAll($crRepoFinal);
        unset($crRepo1, $crRepo2, $crRepo3, $crRepoFinal);

Now XOOPS creates following query:

        SELECT C* FROM `wggithub_repositories` WHERE ((`repo_user` = 'XoopsModules25x') AND (`repo_status` = '2' OR `repo_status` = '3') AND (`repo_prerelease` = '1' OR `repo_release` = '1'))

and now we get expected results :)


